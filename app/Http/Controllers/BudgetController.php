<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon; // Import Carbon for date handling
use Illuminate\Validation\Rule; // Import Rule for validation

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Auth::user();
        $defaultWallet = $user->defaultWallet; // Get the default wallet

        $budgetsQuery = $user->budgets()->with('category', 'wallet');

        // Filter budgets by the default wallet if it exists
        if ($defaultWallet) {
            $budgetsQuery->where('wallet_id', $defaultWallet->id);
        }

        $budgets = $budgetsQuery->latest()->get();
        $categories = $user->categories()->get();

        // Calculate progress for each budget
        $budgets->each(function ($budget) use ($user) {
            $effectiveEndDate = $budget->end_date ? Carbon::parse($budget->end_date) : Carbon::now();
            $startDate = Carbon::parse($budget->start_date);

            // Start with all user transactions
            $query = $user->transactions();

            // Filter by wallet if budget has a wallet_id
            if ($budget->wallet_id) {
                $query->where('wallet_id', $budget->wallet_id);
            }

            $query->whereBetween('transaction_date', [$startDate, $effectiveEndDate]);

            if ($budget->category_id) {
                $query->where('category_id', $budget->category_id);
                if ($budget->category->type === 'expense') {
                    $query->where('type', 'expense');
                } elseif ($budget->category->type === 'income') {
                    $query->where('type', 'income');
                }
            } else {
                $query->where('type', 'expense');
            }

            $currentSpent = $query->sum('amount');
            $budget->current_spent = $currentSpent;
            $budget->progress_percentage = ($budget->amount > 0) ? round(($currentSpent / $budget->amount) * 100, 2) : 0;
        });

        return view('budgets.index', compact('budgets', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = Auth::user();
        $categories = $user->categories()->get();
        $wallets = collect(); // Initialize as empty collection

        if ($user->role === 'premium') {
            $wallets = $user->wallets()->get();
        }

        return view('budgets.create', compact('categories', 'wallets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $rules = [
            'category_id' => ['nullable', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'period' => ['required', 'in:monthly,yearly'], // Fixed validation rule
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'currency' => ['required', 'string', 'max:3'],
        ];

        // Add wallet_id validation only for premium users
        if ($user->role === 'premium') {
            $rules['wallet_id'] = ['nullable', 'exists:wallets,id', Rule::in($user->wallets->pluck('id')->toArray())];
        }

        $validated = $request->validate($rules);


        // Ensure category_id belongs to the user if provided
        if (isset($validated['category_id']) && $validated['category_id'] && !$user->categories()->where('id', $validated['category_id'])->exists()) {
            return back()->withErrors(['category_id' => 'The selected category is invalid.']);
        }

        $user->budgets()->create($validated);

        return redirect()->route('budgets.index')->with('status', 'Budget created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Budget $budget): View
    {
        // Ensure the budget belongs to the authenticated user
        $this->authorize('view', $budget);

        return view('budgets.show', compact('budget'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Budget $budget): View
    {
        // Ensure the budget belongs to the authenticated user
        $this->authorize('update', $budget);

        $user = Auth::user();
        $categories = $user->categories()->get();
        $wallets = collect(); // Initialize as empty collection

        if ($user->role === 'premium') {
            $wallets = $user->wallets()->get();
        }

        return view('budgets.edit', compact('budget', 'categories', 'wallets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Budget $budget): RedirectResponse
    {
        // Ensure the budget belongs to the authenticated user
        $this->authorize('update', $budget);

        $user = Auth::user();

        $rules = [
            'category_id' => ['nullable', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'period' => ['required', 'in:monthly,yearly'], // Fixed validation rule
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'currency' => ['required', 'string', 'max:3'],
        ];

        // Add wallet_id validation only for premium users
        if ($user->role === 'premium') {
            $rules['wallet_id'] = ['nullable', 'exists:wallets,id', Rule::in($user->wallets->pluck('id')->toArray())];
        }

        $validated = $request->validate($rules);

        // Ensure category_id belongs to the user if provided
        if (isset($validated['category_id']) && $validated['category_id'] && !$user->categories()->where('id', $validated['category_id'])->exists()) {
            return back()->withErrors(['category_id' => 'The selected category is invalid.']);
        }

        $budget->update($validated);

        return redirect()->route('budgets.index')->with('status', 'Budget updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget): RedirectResponse
    {
        // Ensure the budget belongs to the authenticated user
        $this->authorize('delete', $budget);

        $budget->delete();

        return redirect()->route('budgets.index')->with('status', 'Budget deleted successfully!');
    }
}
