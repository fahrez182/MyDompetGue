<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\ExchangeRateHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse; // Import RedirectResponse

class DashboardController extends Controller
{
    /**
     * Display the dashboard view with financial summary, recent transactions, and chart data.
     */
    public function index(): View|RedirectResponse // Update return type
    {
        $user = Auth::user();
        $userBaseCurrency = $user->base_currency ?? 'USD';

        // Ensure the user object and its relationships are fresh
        $user->load('defaultWallet', 'wallets'); // Load all wallets as well
        $defaultWallet = $user->defaultWallet;
        $wallets = $user->wallets; // Get all wallets for the dropdown

        // Refresh the default wallet instance to ensure its balance is up-to-date
        if ($defaultWallet) {
            $defaultWallet->refresh();
        }

        // If no default wallet, redirect or show an error
        if (!$defaultWallet) {
            return redirect()->route('profile.edit')->with('error', 'Please set a default wallet to view your dashboard summary.');
        }

        // Convert the default wallet's balance to the user's base currency
        $convertedDefaultWalletBalance = ExchangeRateHelper::convert(
            $defaultWallet->balance,
            $defaultWallet->currency,
            $userBaseCurrency,
            Carbon::now()->toDateString() // Use current date for conversion
        ) ?? $defaultWallet->balance; // Fallback to original balance if conversion fails

        // Convert all wallet balances to the user's base currency for display in the dropdown
        $wallets = $wallets->map(function ($wallet) use ($userBaseCurrency) {
            $wallet->converted_balance = ExchangeRateHelper::convert(
                $wallet->balance,
                $wallet->currency,
                $userBaseCurrency,
                Carbon::now()->toDateString() // Use current date for wallet balance conversion
            ) ?? $wallet->balance; // Fallback to original balance if conversion fails
            return $wallet;
        });


        // Fetch transactions for the default wallet only
        $transactions = $defaultWallet->transactions()->get();

        $totalIncome = 0;
        $totalExpense = 0;

        foreach ($transactions as $transaction) {
            $convertedAmount = ExchangeRateHelper::convert(
                $transaction->amount,
                $transaction->currency,
                $userBaseCurrency,
                $transaction->transaction_date->toDateString()
            );

            Log::debug('DashboardController: Transaction conversion attempt', [
                'transaction_id' => $transaction->id,
                'type' => $transaction->type,
                'original_amount' => $transaction->amount,
                'original_currency' => $transaction->currency,
                'target_currency' => $userBaseCurrency,
                'transaction_date' => $transaction->transaction_date->toDateString(),
                'converted_amount_result' => $convertedAmount,
            ]);

            if ($convertedAmount !== null) {
                if ($transaction->type === 'income') {
                    $totalIncome += $convertedAmount;
                } else {
                    $totalExpense += $convertedAmount;
                }
            } else {
                Log::warning("DashboardController: Could not convert transaction ID {$transaction->id} (Type: {$transaction->type}) from {$transaction->currency} to {$userBaseCurrency}. Using original amount for summary.");
                if ($transaction->type === 'income') {
                    $totalIncome += $transaction->amount;
                } else {
                    $totalExpense += $transaction->amount;
                }
            }
        }

        $balance = $totalIncome - $totalExpense;

        // Ambil 5 transaksi terbaru dari default wallet
        $recentTransactions = $defaultWallet->transactions()
                                        ->orderBy('transaction_date', 'desc')
                                        ->orderBy('created_at', 'desc')
                                        ->take(5)
                                        ->get()
                                        ->map(function ($transaction) use ($userBaseCurrency) {
                                            $convertedAmount = ExchangeRateHelper::convert(
                                                $transaction->amount,
                                                $transaction->currency,
                                                $userBaseCurrency,
                                                $transaction->transaction_date->toDateString()
                                            );
                                            $transaction->converted_amount = $convertedAmount ?? $transaction->amount;
                                            $transaction->converted_currency = $userBaseCurrency;
                                            return $transaction;
                                        });

        // Data untuk grafik (dari default wallet)
        $months = [];
        $monthlyIncome = [];
        $monthlyExpense = [];

        // Ambil data untuk 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M Y');

            $monthlyTransactions = $defaultWallet->transactions()
                                ->whereYear('transaction_date', $month->year)
                                ->whereMonth('transaction_date', $month->month)
                                ->get();

            $currentMonthIncome = 0;
            $currentMonthExpense = 0;

            foreach ($monthlyTransactions as $transaction) {
                $convertedAmount = ExchangeRateHelper::convert(
                    $transaction->amount,
                    $transaction->currency,
                    $userBaseCurrency,
                    $transaction->transaction_date->toDateString()
                );

                Log::debug('DashboardController: Chart transaction conversion attempt', [
                    'transaction_id' => $transaction->id,
                    'type' => $transaction->type,
                    'original_amount' => $transaction->amount,
                    'original_currency' => $transaction->currency,
                    'target_currency' => $userBaseCurrency,
                    'transaction_date' => $transaction->transaction_date->toDateString(),
                    'converted_amount_result' => $convertedAmount,
                ]);

                if ($convertedAmount !== null) {
                    if ($transaction->type === 'income') {
                        $currentMonthIncome += $convertedAmount;
                    } else {
                        $currentMonthExpense += $convertedAmount;
                    }
                } else {
                    Log::warning("DashboardController: Could not convert chart transaction ID {$transaction->id} (Type: {$transaction->type}) from {$transaction->currency} to {$userBaseCurrency}. Using original amount for chart summary.");
                    if ($transaction->type === 'income') {
                        $currentMonthIncome += $transaction->amount;
                    } else {
                        $currentMonthExpense += $transaction->amount;
                    }
                }
            }

            $monthlyIncome[] = $currentMonthIncome;
            $monthlyExpense[] = $currentMonthExpense;
        }

        // Fetch and calculate progress for budgets, filtered by the default wallet
        $budgetsQuery = $user->budgets()->with('category');

        if ($defaultWallet) {
            $budgetsQuery->where('wallet_id', $defaultWallet->id);
        }

        $budgets = $budgetsQuery->latest()->get();

        $budgets->each(function ($budget) use ($user, $userBaseCurrency, $defaultWallet) {
            $effectiveEndDate = $budget->end_date ? Carbon::parse($budget->end_date) : Carbon::now();
            $startDate = Carbon::parse($budget->start_date);

            // Convert budget amount to user's base currency
            // Use $budget->currency as the fromCurrency, which is now available
            $budget->converted_amount = ExchangeRateHelper::convert(
                $budget->amount,
                $budget->currency,
                $userBaseCurrency,
                $startDate->toDateString() // Use budget start date for conversion
            ) ?? $budget->amount; // Fallback to original amount if conversion fails


            // Now filtering transactions by the default wallet
            $query = $defaultWallet->transactions()
                          ->whereBetween('transaction_date', [$startDate, $effectiveEndDate]);

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

            $currentSpent = 0;
            foreach ($query->get() as $transaction) {
                $convertedAmount = ExchangeRateHelper::convert(
                    $transaction->amount,
                    $transaction->currency,
                    $userBaseCurrency,
                    $transaction->transaction_date->toDateString()
                );
                $currentSpent += $convertedAmount ?? $transaction->amount;
            }

            $budget->current_spent = $currentSpent; // This is already in userBaseCurrency
            $budget->progress_percentage = ($budget->converted_amount > 0) ? round(($currentSpent / $budget->converted_amount) * 100, 2) : 0;
        });


        return view('dashboard', compact(
            'totalIncome',
            'totalExpense',
            'balance',
            'recentTransactions',
            'months',
            'monthlyIncome',
            'monthlyExpense',
            'userBaseCurrency',
            'budgets',
            'defaultWallet',
            'wallets', // Pass all wallets to the view
            'convertedDefaultWalletBalance' // Pass the converted default wallet balance
        ));
    }

    /**
     * Recalculates all wallet balances and fixes budget currencies for the authenticated user.
     */
    public function recalculateBalances(): RedirectResponse
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('dashboard')->with('error', 'User not authenticated.');
        }

        Log::info("Recalculating balances for user: {$user->name} (ID: {$user->id}) via dashboard refresh button.");

        // --- Recalculate Wallet Balances ---
        foreach ($user->wallets as $wallet) {
            Log::debug("  - Recalculating balance for wallet: {$wallet->name} (ID: {$wallet->id}, Current Currency: {$wallet->currency})");

            $calculatedBalance = 0;
            $transactions = $wallet->transactions()->orderBy('transaction_date')->orderBy('created_at')->get();

            foreach ($transactions as $transaction) {
                // Convert transaction amount to the wallet's currency
                $convertedAmount = ExchangeRateHelper::convert(
                    $transaction->amount,
                    $transaction->currency,
                    $wallet->currency, // Convert to the wallet's currency
                    $transaction->transaction_date->toDateString()
                );

                if ($convertedAmount === null) {
                    Log::warning("RecalculateBalances: Could not convert transaction ID {$transaction->id} ({$transaction->amount} {$transaction->currency}) to {$wallet->currency} on {$transaction->transaction_date->toDateString()}. Skipping this transaction for balance calculation.");
                    continue; // Skip this transaction if conversion fails
                }

                if ($transaction->type === 'income') {
                    $calculatedBalance += $convertedAmount;
                } else {
                    $calculatedBalance -= $convertedAmount;
                }
            }

            // Update the wallet's balance if there's a significant difference
            if (abs($wallet->balance - $calculatedBalance) > 0.01) {
                $oldBalance = $wallet->balance;
                $wallet->balance = $calculatedBalance;
                $wallet->save();
                Log::info("  - Wallet '{$wallet->name}' balance updated from " . number_format($oldBalance, 2) . " to " . number_format($calculatedBalance, 2) . " {$wallet->currency}");
            } else {
                Log::debug("  - Wallet '{$wallet->name}' balance is already accurate: " . number_format($calculatedBalance, 2) . " {$wallet->currency}");
            }
        }

        // --- Fix Budget Currencies ---
        foreach ($user->budgets as $budget) {
            if ($budget->wallet && $budget->currency !== $budget->wallet->currency) {
                $oldBudgetCurrency = $budget->currency;
                $budget->currency = $budget->wallet->currency;
                $budget->save();
                Log::info("  - Budget '{$budget->id}' currency updated from {$oldBudgetCurrency} to {$budget->currency} (matching wallet '{$budget->wallet->name}')");
            } elseif (!$budget->wallet) {
                Log::warning("  - Budget '{$budget->id}' has no associated wallet. Cannot determine correct currency.");
            } else {
                Log::debug("  - Budget '{$budget->id}' currency is already accurate: {$budget->currency}");
            }
        }

        return redirect()->route('dashboard')->with('success', 'Wallet balances and budget currencies refreshed successfully!');
    }
}
