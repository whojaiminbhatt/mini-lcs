<?php

namespace Database\Factories;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'loan_no' => 'LN' . $this->faker->unique()->numberBetween(1000, 9999),
            'customer_name' => $this->faker->name(),
            'mobile' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'total_amount' => 50000,
            'emi_amount' => 5000,
        ];
    }
}
