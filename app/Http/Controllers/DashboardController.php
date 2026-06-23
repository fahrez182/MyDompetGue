<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Import Carbon

class DashboardController extends Controller
{
    /**
     * Display the dashboard view with financial summary, recent transactions, and chart data.
     */
    public function index(): View
    {
        $user = Auth::user();
        $transactions = Transaction::where('user_id', $user->id)->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Ambil 5 transaksi terbaru
        $recentTransactions = Transaction::where('user_id', $user->id)
                                        ->orderBy('transaction_date', 'desc')
                                        ->orderBy('created_at', 'desc')
                                        ->take(5)
                                        ->get();

        // Data untuk grafik
        $months = [];
        $monthlyIncome = [];
        $monthlyExpense = [];

        // Ambil data untuk 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M Y'); // e.g., Jan 2023

            $income = Transaction::where('user_id', $user->id)
                                ->where('type', 'income')
                                ->whereYear('transaction_date', $month->year)
                                ->whereMonth('transaction_date', $month->month)
                                ->sum('amount');

            $expense = Transaction::where('user_id', $user->id)
                                ->where('type', 'expense')
                                ->whereYear('transaction_date', $month->year)
                                ->whereMonth('transaction_date', $month->month)
                                ->sum('amount');

            $monthlyIncome[] = $income;
            $monthlyExpense[] = $expense;
        }

        return view('dashboard', compact(
            'totalIncome',
            'totalExpense',
            'balance',
            'recentTransactions',
            'months',
            'monthlyIncome',
            'monthlyExpense'
        ));
    }
}
