<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('app:assign-default-wallets')]
#[Description('Ensures every user has exactly one default wallet and assigns transactions if needed')]
class AssignDefaultWallets extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to ensure default wallets for all users...');

        User::chunk(100, function ($users) {
            foreach ($users as $user) {
                DB::transaction(function () use ($user) {
                    $userWallets = $user->wallets;
                    $defaultWallets = $userWallets->where('is_default', true);

                    // Case 1: No wallets at all for the user
                    if ($userWallets->isEmpty()) {
                        $this->comment("User: {$user->name} (ID: {$user->id}) has no wallets. Creating a new default wallet.");

                        $defaultWallet = Wallet::create([
                            'user_id' => $user->id,
                            'name' => 'Default Wallet',
                            'balance' => 0.00,
                            'currency' => $user->base_currency ?? 'USD',
                            'is_default' => true,
                        ]);

                        $this->info("Created default wallet '{$defaultWallet->name}' (ID: {$defaultWallet->id}) for user {$user->name}.");

                        // Assign all existing transactions of the user to this default wallet
                        $updatedTransactionsCount = $user->transactions()->update(['wallet_id' => $defaultWallet->id]);
                        $this->info("Assigned {$updatedTransactionsCount} transactions to default wallet for user {$user->name}.");

                        // Update the wallet balance based on assigned transactions
                        $totalAmount = $defaultWallet->transactions()->sum('amount');
                        $defaultWallet->update(['balance' => $totalAmount]);
                        $this->info("Updated default wallet balance to {$defaultWallet->balance} for user {$user->name}.");

                    }
                    // Case 2: Wallets exist, but no default is marked
                    else if ($defaultWallets->isEmpty()) {
                        $firstWallet = $userWallets->first();
                        if ($firstWallet) {
                            $firstWallet->setDefault(); // This method handles unsetting others and setting itself
                            $this->comment("User: {$user->name} (ID: {$user->id}) had wallets but no default. Set '{$firstWallet->name}' as default.");

                            // Ensure all transactions are linked to this default wallet if they aren't already
                            $updatedTransactionsCount = $user->transactions()->whereNull('wallet_id')->update(['wallet_id' => $firstWallet->id]);
                            if ($updatedTransactionsCount > 0) {
                                $this->info("Assigned {$updatedTransactionsCount} unassigned transactions to default wallet for user {$user->name}.");
                            }

                            // Recalculate balance for the newly set default wallet
                            $totalAmount = $firstWallet->transactions()->sum('amount');
                            $firstWallet->update(['balance' => $totalAmount]);
                            $this->info("Recalculated default wallet balance to {$firstWallet->balance} for user {$user->name}.");
                        }
                    }
                    // Case 3: More than one default wallet (should ideally not happen, but for robustness)
                    else if ($defaultWallets->count() > 1) {
                        $this->warn("User: {$user->name} (ID: {$user->id}) has multiple default wallets. Fixing...");
                        $firstDefault = $defaultWallets->first();
                        $firstDefault->setDefault(); // This will make only one default
                        $this->info("Fixed multiple default wallets for user {$user->name}. '{$firstDefault->name}' is now the sole default.");
                    }
                    // Case 4: Exactly one default wallet (ideal state)
                    else {
                        $this->comment("User: {$user->name} (ID: {$user->id}) already has a single default wallet '{$defaultWallets->first()->name}'. Skipping.");
                    }
                });
            }
        });

        $this->info('Default wallet assignment process completed.');
    }
}
