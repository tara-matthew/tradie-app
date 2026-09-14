<?php

use App\Models\Appointment;
use Carbon\Carbon;

it('updates the appointment and returns it', function () {
    $appointment = Appointment::factory()->create();
    $newScheduledAt = now()->addWeeks(2)->toDateTimeString();

    $this->putJson(route('api.v1.appointments.update', $appointment), [
        'scheduled_at' => $newScheduledAt,
    ])
        ->assertOk()
        ->assertJsonPath('data.scheduled_at', Carbon::parse($newScheduledAt)->toJSON());

    $this->assertDatabaseHas('appointments', [
        'id' => $appointment->id,
        'scheduled_at' => $newScheduledAt,
    ]);
});

it('fails validation when scheduled_at is in the past', function () {
    $appointment = Appointment::factory()->create();

    $this->putJson(route('api.v1.appointments.update', $appointment), [
        'scheduled_at' => now()->subDay()->toDateTimeString(),
    ])
        ->assertInvalid([
            'scheduled_at' => 'The scheduled at field must be a date after now.',
        ]);
});

it('returns 404 when the appointment does not exist', function () {
    $this->putJson(route('api.v1.appointments.update', 999), [
        'scheduled_at' => now()->addWeek()->toDateTimeString(),
    ])
        ->assertNotFound();
});
