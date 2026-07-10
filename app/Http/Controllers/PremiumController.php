<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Illuminate\Http\RedirectResponse; // Import RedirectResponse
use App\Models\Transaction; // Import the Transaction model
use App\Models\Category; // Import the Category model
use App\Models\Wallet; // Import the Wallet model

class PremiumController extends Controller
{
    /**
     * Display a listing of the premium features.
     */
    public function index(): View
    {
        $user = Auth::user();
        return view('premium.index', [
            'userRole' => $user->role,
        ]);
    }

    /**
     * Handle the upgrade to premium.
     */
    public function upgrade(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Only allow basic users to upgrade
        if ($user->role === 'basic') {
            $user->role = 'premium';
            $user->save();

            return redirect()->route('premium.index')->with('status', 'Congratulations! You have successfully upgraded to Premium!');
        }

        return redirect()->route('premium.index')->with('error', 'You are already a Premium user or not eligible to upgrade.');
    }

    /**
     * Display the advanced reporting page.
     */
    public function advancedReporting(Request $request): View
    {
        $user = Auth::user();
        $transactionsQuery = $user->transactions();

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $type = $request->input('type');
        $categoryId = $request->input('category_id');

        if ($startDate) {
            $transactionsQuery->whereDate('date', '>=', $startDate);
        }

        if ($endDate) {
            $transactionsQuery->whereDate('date', '<=', $endDate);
        }

        if ($type) {
            $transactionsQuery->where('type', $type);
        }

        if ($categoryId) {
            $transactionsQuery->where('category_id', $categoryId);
        }

        $transactions = $transactionsQuery->latest()->get();
        $categories = $user->categories()->get(); // Fetch categories for the current user

        // Calculate summary statistics
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        // Get the user's base currency
        $currencySymbol = $user->base_currency ?? 'Rp'; // Default to 'Rp' if not set

        // Calculate total balance across all wallets
        $totalWalletBalance = $user->wallets()->sum('balance');

        // Prepare data for category chart
        $categoryExpenses = $transactions->where('type', 'expense')
                                         ->groupBy('category_id')
                                         ->mapWithKeys(function ($group, $categoryId) use ($categories) {
                                             $categoryName = $categories->find($categoryId)->name ?? 'Uncategorized';
                                             return [$categoryName => $group->sum('amount')];
                                         });

        $categoryIncomes = $transactions->where('type', 'income')
                                        ->groupBy('category_id')
                                        ->mapWithKeys(function ($group, $categoryId) use ($categories) {
                                            $categoryName = $categories->find($categoryId)->name ?? 'Uncategorized';
                                            return [$categoryName => $group->sum('amount')];
                                        });

        return view('premium.reporting.advanced', [
            'transactions' => $transactions,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'type' => $type,
            'categoryId' => $categoryId,
            'categories' => $categories,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netBalance' => $netBalance,
            'categoryExpenses' => $categoryExpenses,
            'categoryIncomes' => $categoryIncomes,
            'currencySymbol' => $currencySymbol,
            'totalWalletBalance' => $totalWalletBalance, // Pass total wallet balance to the view
        ]);
    }
}
