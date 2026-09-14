<?php

use App\Http\Requests\UpdateAppointmentRequest;
use Illuminate\Support\Facades\Validator;

it('passes with a future date', function () {
    $validator = Validator::make([
        'scheduled_at' => now()->addWeek()->toDateTimeString(),
    ], (new UpdateAppointmentRequest)->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails when scheduled_at is missing', function () {
    $validator = Validator::make([], (new UpdateAppointmentRequest)->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('scheduled_at'))->toBeTrue();
});

it('fails validation for invalid input', function (array $data) {
    $validator = Validator::make($data, (new UpdateAppointmentRequest)->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('scheduled_at'))->toBeTrue();
})->with([
    'not a date' => [['scheduled_at' => 'not-a-date']],
    'in the past' => [['scheduled_at' => now()->subDay()->toDateTimeString()]],
    'right now' => [['scheduled_at' => now()->toDateTimeString()]],
]);
