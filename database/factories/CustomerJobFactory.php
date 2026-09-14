<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerJob;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomerJob>
 */
class CustomerJobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
        ];
    }
}
