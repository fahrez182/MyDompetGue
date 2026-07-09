<?php

namespace App\Console\Commands;

use App\Helpers\ExchangeRateHelper;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

#[Signature('app:recalculate-wallet-balances')]
#[Description('Recalculates all wallet balances and fixes budget currencies based on their transactions/wallets.')]
class RecalculateWalletBalances extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting wallet and budget data recalculation...');

        $users = User::with('wallets.transactions', 'budgets.wallet')->get(); // Eager load relationships

        foreach ($users as $user) {
            $this->comment("Processing user: {$user->name} (ID: {$user->id})");

            // --- Recalculate Wallet Balances ---
            foreach ($user->wallets as $wallet) {
                $this->line("  - Recalculating balance for wallet: {$wallet->name} (ID: {$wallet->id}, Current Currency: {$wallet->currency})");

                $calculatedBalance = 0;
                $transactions = $wallet->transactions; // Already eager loaded

                foreach ($transactions as $transaction) {
                    // Convert transaction amount to the wallet's currency
                    $convertedAmount = ExchangeRateHelper::convert(
                        $transaction->amount,
                        $transaction->currency,
                        $wallet->currency, // Convert to the wallet's currency
                        $transaction->transaction_date->toDateString()
                    );

                    if ($convertedAmount === null) {
                        $this->warn("    Warning: Could not convert transaction ID {$transaction->id} ({$transaction->amount} {$transaction->currency}) to {$wallet->currency} on {$transaction->transaction_date->toDateString()}. Skipping this transaction for balance calculation.");
                        Log::warning("RecalculateWalletBalances: Could not convert transaction ID {$transaction->id} from {$transaction->currency} to {$wallet->currency}.");
                        continue; // Skip this transaction if conversion fails
                    }

                    if ($transaction->type === 'income') {
                        $calculatedBalance += $convertedAmount;
                    } else {
                        $calculatedBalance -= $convertedAmount;
                    }
                }

                // Update the wallet's balance
                if (abs($wallet->balance - $calculatedBalance) > 0.01) { // Check for significant difference
                    $oldBalance = $wallet->balance;
                    $wallet->balance = $calculatedBalance;
                    $wallet->save();
                    $this->info("  - Wallet '{$wallet->name}' balance updated from " . number_format($oldBalance, 2) . " to " . number_format($calculatedBalance, 2) . " {$wallet->currency}");
                } else {
                    $this->line("  - Wallet '{$wallet->name}' balance is already accurate: " . number_format($calculatedBalance, 2) . " {$wallet->currency}");
                }
            }

            // --- Fix Budget Currencies ---
            foreach ($user->budgets as $budget) {
                if ($budget->wallet && $budget->currency !== $budget->wallet->currency) {
                    $oldBudgetCurrency = $budget->currency;
                    $budget->currency = $budget->wallet->currency;
                    $budget->save();
                    $this->info("  - Budget '{$budget->id}' currency updated from {$oldBudgetCurrency} to {$budget->currency} (matching wallet '{$budget->wallet->name}')");
                } elseif (!$budget->wallet) {
                    $this->warn("  - Warning: Budget '{$budget->id}' has no associated wallet. Cannot determine correct currency.");
                } else {
                    $this->line("  - Budget '{$budget->id}' currency is already accurate: {$budget->currency}");
                }
            }
        }

        $this->info('Wallet and budget data recalculation completed.');
    }
}
