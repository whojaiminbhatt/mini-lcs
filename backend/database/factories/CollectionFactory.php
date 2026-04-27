<?php

namespace Database\Factories;

use App\Models\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Collection>
 */
class CollectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'loan_id' => \App\Models\Loan::factory(),
            'amount_paid' => 1000,
            'payment_mode' => 'cash',
            'location' => 'Branch',
            'collected_at' => now(),
            'collected_by' => \App\Models\User::factory(),
        ];
    }
}
