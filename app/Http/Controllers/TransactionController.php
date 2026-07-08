<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ExchangeRateHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    const SUPPORTED_CURRENCIES = ['USD', 'IDR', 'EUR', 'GBP', 'JPY', 'AUD', 'CAD', 'CHF', 'CNY', 'SGD'];

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = auth()->user();
        $userBaseCurrency = $user->base_currency ?? 'USD';

        $walletId = request('wallet_id');
        $transactionsQuery = $user->transactions();

        if ($walletId) {
            $transactionsQuery->where('wallet_id', $walletId);
        }

        $transactions = $transactionsQuery->latest()->paginate(10);

        $transactions->getCollection()->transform(function ($transaction) use ($userBaseCurrency) {
            $convertedAmount = ExchangeRateHelper::convert(
                $transaction->amount,
                $transaction->currency,
                $userBaseCurrency,
                $transaction->transaction_date->toDateString()
            );
            $transaction->converted_amount = $convertedAmount ?? $transaction->amount;
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

        $wallets = $user->wallets;

        return view('transactions.index', compact('transactions', 'userBaseCurrency', 'wallets', 'walletId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = auth()->user();
        $categories = $user->categories()->get();
        $userBaseCurrency = $user->base_currency ?? 'USD';

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
        $user = auth()->user();
        $defaultWallet = $user->defaultWallet;

        if (!$defaultWallet) {
            return redirect()->back()->with('error', 'You must have a default wallet to create transactions. Please create one.');
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'size:3', 'in:' . implode(',', self::SUPPORTED_CURRENCIES)],
            'type' => ['required', 'in:income,expense'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'transaction_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        // Assign to the default wallet
        $validated['wallet_id'] = $defaultWallet->id;

        $transaction = $user->transactions()->create($validated);

        // Update wallet balance
        $amount = ($validated['type'] === 'income') ? $validated['amount'] : -$validated['amount'];
        $defaultWallet->increment('balance', $amount);

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

        $user = auth()->user();
        $categories = $user->categories()->get();
        $userBaseCurrency = $user->base_currency ?? 'USD';

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

        // Revert old transaction amount from its wallet
        $oldAmount = ($transaction->type === 'income') ? $transaction->amount : -$transaction->amount;
        $transaction->wallet->decrement('balance', $oldAmount);

        $transaction->update($validated);

        // Apply new transaction amount to its wallet
        $newAmount = ($validated['type'] === 'income') ? $validated['amount'] : -$validated['amount'];
        $transaction->wallet->increment('balance', $newAmount);

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        $this->authorize('delete', $transaction);

        // Revert wallet balance before deleting transaction
        $wallet = $transaction->wallet;
        if ($wallet) {
            $amount = ($transaction->type === 'income') ? $transaction->amount : -$transaction->amount;
            $wallet->decrement('balance', $amount);
        }

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully!');
    }
}
