<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\ExchangeRateHelper;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display the dashboard view with financial summary, recent transactions, and chart data.
     */
    public function index(): View
    {
        $user = Auth::user();
        $userBaseCurrency = $user->base_currency ?? 'USD';

        $transactions = Transaction::where('user_id', $user->id)->get();

        $totalIncome = 0;
        $totalExpense = 0;

        foreach ($transactions as $transaction) {
            $convertedAmount = ExchangeRateHelper::convert(
                $transaction->amount,
                $transaction->currency,
                $userBaseCurrency,
                $transaction->transaction_date->toDateString() // Use transaction date for rate
            );

            Log::debug('DashboardController: Transaction conversion attempt', [
                'transaction_id' => $transaction->id,
                'type' => $transaction->type, // Added type for easier debugging
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

        // Ambil 5 transaksi terbaru dan tambahkan converted_amount
        $recentTransactions = Transaction::where('user_id', $user->id)
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
                                            $transaction->converted_amount = $convertedAmount ?? $transaction->amount; // Fallback to original if conversion fails
                                            $transaction->converted_currency = $userBaseCurrency;
                                            return $transaction;
                                        });

        // Data untuk grafik
        $months = [];
        $monthlyIncome = [];
        $monthlyExpense = [];

        // Ambil data untuk 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M Y'); // e.g., Jan 2023

            $monthlyTransactions = Transaction::where('user_id', $user->id)
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
                    'type' => $transaction->type, // Added type for easier debugging
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

        return view('dashboard', compact(
            'totalIncome',
            'totalExpense',
            'balance',
            'recentTransactions',
            'months',
            'monthlyIncome',
            'monthlyExpense',
            'userBaseCurrency' // Pass user's base currency to the view
        ));
    }
}
