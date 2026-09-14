<?php

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\CustomerJob;

it('returns the appointment with its job and customer loaded', function () {
    $customer = Customer::factory()->create();
    $job = CustomerJob::factory()->create(['customer_id' => $customer->id]);
    $appointment = Appointment::factory()->create(['customer_job_id' => $job->id]);

    $this->getJson(route('api.v1.appointments.show', $appointment))
        ->assertOk()
        ->assertJsonPath('data.id', $appointment->id)
        ->assertJsonPath('data.customer_job_id', $job->id)
        ->assertJsonPath('data.customer_job.id', $job->id)
        ->assertJsonPath('data.customer_job.customer.id', $customer->id);
});

it('returns 404 when the appointment does not exist', function () {
    $this->getJson(route('api.v1.appointments.show', 999))
        ->assertNotFound();
});
