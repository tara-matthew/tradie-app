<?php

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\CustomerJob;
use Illuminate\Testing\Fluent\AssertableJson;

it('returns the customer with its jobs and appointments loaded', function () {
    $customer = Customer::factory()->create();
    $job = CustomerJob::factory()->create(['customer_id' => $customer->id]);
    $appointment = Appointment::factory()->create(['customer_job_id' => $job->id]);

    $this->getJson(route('api.v1.customers.show', $customer))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn (AssertableJson $json) => $json
                ->where('id', $customer->id)
                ->where('name', $customer->name)
                ->where('phone', $customer->phone)
                ->where('email', $customer->email)
                ->has('jobs', 1, fn (AssertableJson $json) => $json
                    ->where('id', $job->id)
                    ->etc())
                ->has('appointments', 1, fn (AssertableJson $json) => $json
                    ->where('id', $appointment->id)
                    ->etc())
                ->etc()));
});

it('returns 404 when the customer does not exist', function () {
    $this->getJson(route('api.v1.customers.show', 999))
        ->assertNotFound();
});
