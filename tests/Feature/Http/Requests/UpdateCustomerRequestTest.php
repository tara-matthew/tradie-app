<?php

use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Support\Facades\Validator;

it('passes with valid data', function () {
    $validator = Validator::make([
        'name' => 'Jane Doe',
        'phone' => '0400123456',
        'email' => 'jane@example.com',
    ], (new UpdateCustomerRequest)->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails required fields together on an empty payload', function () {
    $validator = Validator::make([], (new UpdateCustomerRequest)->rules());

    expect($validator->errors()->keys())->toEqual(['name', 'phone']);
});

it('fails validation for invalid input', function (array $data, string $invalidField) {
    $validator = Validator::make([
        'name' => 'Jane Doe',
        'phone' => '0400123456',
        'email' => 'jane@example.com',
        ...$data,
    ], (new UpdateCustomerRequest)->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has($invalidField))->toBeTrue();
})->with([
    'name too long' => [['name' => str_repeat('a', 256)], 'name'],
    'phone too long' => [['phone' => str_repeat('1', 256)], 'phone'],
    'email invalid format' => [['email' => 'not-an-email'], 'email'],
]);

// The `ignore()` behaviour (a customer keeping their own email vs. taking
// another customer's) depends on a bound route parameter, so it's covered
// by the endpoint test instead of faked here.
