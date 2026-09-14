<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\CustomerJob;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_job_id' => CustomerJob::factory(),
            'scheduled_at' => fake()->dateTimeBetween('now', '+2 weeks'),
        ];
    }
}
