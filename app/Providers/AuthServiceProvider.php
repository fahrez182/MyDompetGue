<?php

namespace App\Providers;

use App\Models\Transaction;
use App\Policies\TransactionPolicy;
use App\Models\Budget; // Import Budget model
use App\Policies\BudgetPolicy; // Import BudgetPolicy
use App\Models\RecurringTransaction; // Import RecurringTransaction model
use App\Policies\RecurringTransactionPolicy; // Import RecurringTransactionPolicy
use App\Models\Wallet; // Import Wallet model
use App\Policies\WalletPolicy; // Import WalletPolicy
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Transaction::class => TransactionPolicy::class,
        Budget::class => BudgetPolicy::class, // Register BudgetPolicy
        RecurringTransaction::class => RecurringTransactionPolicy::class, // Register RecurringTransactionPolicy
        Wallet::class => WalletPolicy::class, // Register WalletPolicy
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
