<?php

namespace App\Http\Controllers;

use App\Models\RecurringTransaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class RecurringTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Auth::user();
        $recurringTransactions = $user->recurringTransactions()->with('category')->latest('next_run_date')->get();
        $categories = $user->categories()->get();

        return view('recurring-transactions.index', compact('recurringTransactions', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = Auth::user();
        $categories = $user->categories()->get();
        return view('recurring-transactions.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'max:3'],
            'type' => ['required', 'in:income,expense'],
            'frequency' => ['required', 'in:daily,weekly,monthly,yearly'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        // Ensure category_id belongs to the user if provided
        if ($validated['category_id'] && !$user->categories()->where('id', $validated['category_id'])->exists()) {
            return back()->withErrors(['category_id' => 'The selected category is invalid.']);
        }

        // Calculate next_run_date based on start_date and frequency
        $nextRunDate = Carbon::parse($validated['start_date']);
        switch ($validated['frequency']) {
            case 'daily':
                // If start_date is in the future, next_run_date is start_date
                if ($nextRunDate->isFuture()) {
                    // Do nothing, it's already start_date
                } else {
                    // If start_date is in the past or today, next_run_date is tomorrow
                    $nextRunDate->addDay();
                }
                break;
            case 'weekly':
                if ($nextRunDate->isFuture()) {
                    // Do nothing
                } else {
                    $nextRunDate->addWeek();
                }
                break;
            case 'monthly':
                if ($nextRunDate->isFuture()) {
                    // Do nothing
                } else {
                    $nextRunDate->addMonth();
                }
                break;
            case 'yearly':
                if ($nextRunDate->isFuture()) {
                    // Do nothing
                } else {
                    $nextRunDate->addYear();
                }
                break;
        }
        // Ensure next_run_date is not past end_date if end_date exists
        if (isset($validated['end_date']) && $nextRunDate->greaterThan(Carbon::parse($validated['end_date']))) {
            $nextRunDate = null; // No more runs
        }


        $user->recurringTransactions()->create(array_merge($validated, [
            'next_run_date' => $nextRunDate,
            'last_run_date' => null, // Initially null
            'currency' => $validated['currency'] ?? ($user->base_currency ?? 'IDR'), // Use user's base currency as default if not provided
        ]));

        return redirect()->route('recurring-transactions.index')->with('status', 'Recurring transaction created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(RecurringTransaction $recurringTransaction): View
    {
        $this->authorize('view', $recurringTransaction);
        return view('recurring-transactions.show', compact('recurringTransaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RecurringTransaction $recurringTransaction): View
    {
        $this->authorize('update', $recurringTransaction);
        $user = Auth::user();
        $categories = $user->categories()->get();
        return view('recurring-transactions.edit', compact('recurringTransaction', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RecurringTransaction $recurringTransaction): RedirectResponse
    {
        $this->authorize('update', $recurringTransaction);
        $user = Auth::user();

        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'max:3'],
            'type' => ['required', 'in:income,expense'],
            'frequency' => ['required', 'in:daily,weekly,monthly,yearly'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        // Ensure category_id belongs to the user if provided
        if ($validated['category_id'] && !$user->categories()->where('id', $validated['category_id'])->exists()) {
            return back()->withErrors(['category_id' => 'The selected category is invalid.']);
        }

        // Recalculate next_run_date if start_date or frequency changed
        $nextRunDate = Carbon::parse($validated['start_date']);
        switch ($validated['frequency']) {
            case 'daily':
                if ($nextRunDate->isFuture()) {
                    // Do nothing
                } else {
                    $nextRunDate->addDay();
                }
                break;
            case 'weekly':
                if ($nextRunDate->isFuture()) {
                    // Do nothing
                } else {
                    $nextRunDate->addWeek();
                }
                break;
            case 'monthly':
                if ($nextRunDate->isFuture()) {
                    // Do nothing
                } else {
                    $nextRunDate->addMonth();
                }
                break;
            case 'yearly':
                if ($nextRunDate->isFuture()) {
                    // Do nothing
                } else {
                    $nextRunDate->addYear();
                }
                break;
        }
        // Ensure next_run_date is not past end_date if end_date exists
        if (isset($validated['end_date']) && $nextRunDate->greaterThan(Carbon::parse($validated['end_date']))) {
            $nextRunDate = null; // No more runs
        }

        $recurringTransaction->update(array_merge($validated, [
            'next_run_date' => $nextRunDate,
            'currency' => $validated['currency'] ?? ($user->base_currency ?? 'IDR'),
        ]));

        return redirect()->route('recurring-transactions.index')->with('status', 'Recurring transaction updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RecurringTransaction $recurringTransaction): RedirectResponse
    {
        $this->authorize('delete', $recurringTransaction);
        $recurringTransaction->delete();
        return redirect()->route('recurring-transactions.index')->with('status', 'Recurring transaction deleted successfully!');
    }
}
