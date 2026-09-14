<?php

use App\Models\Customer;
use App\Models\CustomerJob;

it('creates an appointment for the job and returns it', function () {
    $customer = Customer::factory()->create();
    $job = CustomerJob::factory()->create(['customer_id' => $customer->id]);

    $this->postJson(route('api.v1.appointments.store', $job), [
        'scheduled_at' => now()->addWeek()->toDateTimeString(),
    ])
        ->assertCreated()
        ->assertJsonPath('data.customer_job_id', $job->id);

    $this->assertDatabaseHas('appointments', [
        'customer_job_id' => $job->id,
    ]);
});

it('fails validation when scheduled_at is in the past', function () {
    $job = CustomerJob::factory()->create();

    $this->postJson(route('api.v1.appointments.store', $job), [
        'scheduled_at' => now()->subDay()->toDateTimeString(),
    ])
        ->assertInvalid([
            'scheduled_at' => 'The scheduled at field must be a date after now.',
        ]);
});

it('returns 404 when the job does not exist', function () {
    $this->postJson(route('api.v1.appointments.store', 999), [
        'scheduled_at' => now()->addWeek()->toDateTimeString(),
    ])
        ->assertNotFound();
});
