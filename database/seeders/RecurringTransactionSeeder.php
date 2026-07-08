<?php

namespace Database\Seeders;

use App\Models\RecurringTransaction;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecurringTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure there are users and categories to associate with recurring transactions
        if (User::count() === 0) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        if (Category::count() === 0) {
            Category::factory()->count(5)->create();
        }

        // Create 10 recurring transactions, associating them with existing users and categories
        RecurringTransaction::factory()->count(10)->create([
            'user_id' => User::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
        ]);
    }
}
