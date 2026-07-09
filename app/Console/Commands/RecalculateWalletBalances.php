<?php

namespace App\Console\Commands;

use App\Helpers\ExchangeRateHelper;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

#[Signature('app:recalculate-wallet-balances')]
#[Description('Recalculates all wallet balances based on their transactions.')]
class RecalculateWalletBalances extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting wallet balance recalculation...');

        $users = User::all();

        foreach ($users as $user) {
            $this->comment("Processing user: {$user->name} (ID: {$user->id})");

            foreach ($user->wallets as $wallet) {
                $this->line("  - Recalculating balance for wallet: {$wallet->name} (ID: {$wallet->id}, Currency: {$wallet->currency})");

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
                        $this->warn("    Warning: Could not convert transaction ID {$transaction->id} from {$transaction->currency} to {$wallet->currency}. Skipping this transaction for balance calculation.");
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
                $wallet->balance = $calculatedBalance;
                $wallet->save();

                $this->info("  - Wallet '{$wallet->name}' balance updated to: " . number_format($calculatedBalance, 2) . " {$wallet->currency}");
            }
        }

        $this->info('Wallet balance recalculation completed.');
    }
}
