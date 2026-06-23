<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ExchangeRateHelper;
use Illuminate\Support\Facades\Log; // Import Log facade

class TransactionController extends Controller
{
    // Define a list of supported currencies
    const SUPPORTED_CURRENCIES = ['USD', 'IDR', 'EUR', 'GBP', 'JPY', 'AUD', 'CAD', 'CHF', 'CNY', 'SGD'];

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = auth()->user();
        $userBaseCurrency = $user->base_currency ?? 'USD'; // Reverted to use user's base_currency

        $transactions = $user->transactions()->latest()->paginate(10);

        // Add converted amount to each transaction
        $transactions->getCollection()->transform(function ($transaction) use ($userBaseCurrency) {
            $convertedAmount = ExchangeRateHelper::convert(
                $transaction->amount,
                $transaction->currency,
                $userBaseCurrency,
                $transaction->transaction_date->toDateString()
            );
            $transaction->converted_amount = $convertedAmount ?? $transaction->amount; // Fallback to original if conversion fails
            $transaction->converted_currency = $userBaseCurrency;

            Log::debug('TransactionController: Transaction conversion in index view', [
                'transaction_id' => $transaction->id,
                'original_amount' => $transaction->amount,
                'original_currency' => $transaction->currency,
                'target_currency' => $userBaseCurrency,
                'transaction_date' => $transaction->transaction_date->toDateString(),
                'converted_amount_result' => $convertedAmount,
            ]);

            return $transaction;
        });

        return view('transactions.index', compact('transactions', 'userBaseCurrency'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = auth()->user()->categories()->get();
        $userBaseCurrency = Auth::user()->base_currency ?? 'USD'; // Reverted to use user's base_currency

        return view('transactions.create', [
            'categories' => $categories,
            'currencies' => self::SUPPORTED_CURRENCIES,
            'userBaseCurrency' => $userBaseCurrency,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'size:3', 'in:' . implode(',', self::SUPPORTED_CURRENCIES)],
            'type' => ['required', 'in:income,expense'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'transaction_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        auth()->user()->transactions()->create($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // This method is not used in the current setup, but can be implemented if needed.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction): View
    {
        $this->authorize('update', $transaction);

        $categories = auth()->user()->categories()->get();
        $userBaseCurrency = Auth::user()->base_currency ?? 'USD'; // Reverted to use user's base_currency

        return view('transactions.edit', [
            'transaction' => $transaction,
            'categories' => $categories,
            'currencies' => self::SUPPORTED_CURRENCIES,
            'userBaseCurrency' => $userBaseCurrency,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        $this->authorize('update', $transaction);

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'size:3', 'in:' . implode(',', self::SUPPORTED_CURRENCIES)],
            'type' => ['required', 'in:income,expense'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'transaction_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        $this->authorize('delete', $transaction);

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully!');
    }
}
