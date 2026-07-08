<?php

namespace Database\Factories;

use App\Models\Budget;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Budget>
 */
class BudgetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(), // Assuming categories exist or will be created
            'amount' => $this->faker->randomFloat(2, 100, 5000),
            'period' => $this->faker->randomElement(['monthly', 'yearly']),
            'start_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            // Corrected: Make end_date optionally null
            'end_date' => $this->faker->boolean(70) ? $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d') : null,
        ];
    }
}
