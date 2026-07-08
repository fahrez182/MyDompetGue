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

        // If no default wallet, redirect or show an error
        if (!$defaultWallet) {
            return redirect()->route('profile.edit')->with('error', 'Please set a default wallet to view your dashboard summary.');
        }

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

        $budgets->each(function ($budget) use ($user, $userBaseCurrency, $defaultWallet) { // Pass $defaultWallet
            $effectiveEndDate = $budget->end_date ? Carbon::parse($budget->end_date) : Carbon::now();
            $startDate = Carbon::parse($budget->start_date);

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

            $budget->current_spent = $currentSpent;
            $budget->progress_percentage = ($budget->amount > 0) ? round(($currentSpent / $budget->amount) * 100, 2) : 0;
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
            'wallets' // Pass all wallets to the view
        ));
    }
}
