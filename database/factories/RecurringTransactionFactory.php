<?php

namespace Database\Factories;

use App\Models\RecurringTransaction;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends Factory<RecurringTransaction>
 */
class RecurringTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 year', 'now');
        $frequency = $this->faker->randomElement(['daily', 'weekly', 'monthly', 'yearly']);
        $type = $this->faker->randomElement(['income', 'expense']);

        $nextRunDate = Carbon::parse($startDate);
        switch ($frequency) {
            case 'daily':
                $nextRunDate->addDay();
                break;
            case 'weekly':
                $nextRunDate->addWeek();
                break;
            case 'monthly':
                $nextRunDate->addMonth();
                break;
            case 'yearly':
                $nextRunDate->addYear();
                break;
        }

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(), // Assuming categories exist or will be created
            'description' => $this->faker->sentence(3),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'currency' => $this->faker->randomElement(['IDR', 'USD', 'EUR']),
            'type' => $type,
            'frequency' => $frequency,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $this->faker->boolean(50) ? $this->faker->dateTimeBetween($startDate, '+2 years')->format('Y-m-d') : null,
            // Corrected: Make last_run_date optionally null
            'last_run_date' => $this->faker->boolean(80) ? $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d') : null,
            'next_run_date' => $nextRunDate->format('Y-m-d'),
        ];
    }
}
