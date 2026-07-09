<?php

namespace App\Http\Controllers;

use App\Helpers\ExchangeRateHelper;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class TransactionController extends Controller
{
    // const SUPPORTED_CURRENCIES = ['USD', 'IDR', 'EUR', 'GBP', 'JPY', 'AUD', 'CAD', 'CHF', 'CNY', 'SGD']; // Removed

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
        $supportedCurrencies = config('currencies.supported'); // Use config

        return view('transactions.create', [
            'categories' => $categories,
            'currencies' => $supportedCurrencies, // Updated to use config
            'userBaseCurrency' => $userBaseCurrency,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $userBaseCurrency = $user->base_currency ?? 'USD';
        $defaultWallet = $user->defaultWallet;

        if (! $defaultWallet) {
            return redirect()->back()->with('error', 'You must have a default wallet to create transactions. Please create one.');
        }

        $supportedCurrencies = implode(',', config('currencies.supported')); // Use config

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'size:3', 'in:'.$supportedCurrencies], // Updated to use config
            'type' => ['required', 'in:income,expense'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'transaction_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        // Assign to the default wallet
        $validated['wallet_id'] = $defaultWallet->id;

        $transaction = $user->transactions()->create($validated);

        // Convert amount to user's base currency for wallet update
        $transactionAmount = $validated['amount'];
        $transactionCurrency = $validated['currency'];
        $transactionDate = $validated['transaction_date'];

        $convertedAmountForWallet = ExchangeRateHelper::convert(
            $transactionAmount,
            $transactionCurrency,
            $userBaseCurrency,
            $transactionDate
        );

        // Use the converted amount for wallet balance update
        $amountToUpdateWallet = ($validated['type'] === 'income') ? $convertedAmountForWallet : -$convertedAmountForWallet;
        $defaultWallet->increment('balance', $amountToUpdateWallet);

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
        $supportedCurrencies = config('currencies.supported'); // Use config

        return view('transactions.edit', [
            'transaction' => $transaction,
            'categories' => $categories,
            'currencies' => $supportedCurrencies, // Updated to use config
            'userBaseCurrency' => $userBaseCurrency,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        $this->authorize('update', $transaction);

        $user = auth()->user();
        $userBaseCurrency = $user->base_currency ?? 'USD';

        $supportedCurrencies = implode(',', config('currencies.supported')); // Use config

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'size:3', 'in:'.$supportedCurrencies], // Updated to use config
            'type' => ['required', 'in:income,expense'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'transaction_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        // Revert old transaction amount from its wallet (converted to user's base currency)
        $oldTransactionAmount = $transaction->amount;
        $oldTransactionCurrency = $transaction->currency;
        $oldTransactionDate = $transaction->transaction_date->toDateString();

        $convertedOldAmountForWallet = ExchangeRateHelper::convert(
            $oldTransactionAmount,
            $oldTransactionCurrency,
            $userBaseCurrency,
            $oldTransactionDate
        );

        $amountToRevertWallet = ($transaction->type === 'income') ? $convertedOldAmountForWallet : -$convertedOldAmountForWallet;
        $transaction->wallet->decrement('balance', $amountToRevertWallet);

        $transaction->update($validated);

        // Apply new transaction amount to its wallet (converted to user's base currency)
        $newTransactionAmount = $validated['amount'];
        $newTransactionCurrency = $validated['currency'];
        $newTransactionDate = $validated['transaction_date'];

        $convertedNewAmountForWallet = ExchangeRateHelper::convert(
            $newTransactionAmount,
            $newTransactionCurrency,
            $userBaseCurrency,
            $newTransactionDate
        );

        $amountToUpdateWallet = ($validated['type'] === 'income') ? $convertedNewAmountForWallet : -$convertedNewAmountForWallet;
        $transaction->wallet->increment('balance', $amountToUpdateWallet);

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        $this->authorize('delete', $transaction);

        $user = auth()->user();
        $userBaseCurrency = $user->base_currency ?? 'USD';

        // Revert wallet balance before deleting transaction (converted to user's base currency)
        $wallet = $transaction->wallet;
        if ($wallet) {
            $transactionAmount = $transaction->amount;
            $transactionCurrency = $transaction->currency;
            $transactionDate = $transaction->transaction_date->toDateString();

            $convertedAmountForWallet = ExchangeRateHelper::convert(
                $transactionAmount,
                $transactionCurrency,
                $userBaseCurrency,
                $transactionDate
            );

            $amountToRevertWallet = ($transaction->type === 'income') ? $convertedAmountForWallet : -$convertedAmountForWallet;
            $wallet->decrement('balance', $amountToRevertWallet);
        }

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully!');
    }
}
