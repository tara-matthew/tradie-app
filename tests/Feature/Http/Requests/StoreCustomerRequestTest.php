<?php

use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

it('passes with valid data', function () {
    $validator = Validator::make([
        'name' => 'Jane Doe',
        'phone' => '0400123456',
        'email' => 'jane@example.com',
    ], (new StoreCustomerRequest)->rules());

    expect($validator->passes())->toBeTrue();
});

it('passes without an email', function () {
    $validator = Validator::make([
        'name' => 'Jane Doe',
        'phone' => '0400123456',
    ], (new StoreCustomerRequest)->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails required fields together on an empty payload', function () {
    $validator = Validator::make([], (new StoreCustomerRequest)->rules());

    expect($validator->errors()->keys())->toEqual(['name', 'phone']);
});

it('fails validation for invalid input', function (array $data, string $invalidField) {
    $validator = Validator::make([
        'name' => 'Jane Doe',
        'phone' => '0400123456',
        'email' => 'jane@example.com',
        ...$data,
    ], (new StoreCustomerRequest)->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has($invalidField))->toBeTrue();
})->with([
    'name too long' => [['name' => str_repeat('a', 256)], 'name'],
    'phone too long' => [['phone' => str_repeat('1', 256)], 'phone'],
    'email invalid format' => [['email' => 'not-an-email'], 'email'],
    'email too long' => [['email' => str_repeat('a', 250).'@example.com'], 'email'],
]);

it('fails when the email is already taken', function () {
    Customer::factory()->create(['email' => 'jane@example.com']);

    $validator = Validator::make([
        'name' => 'Jane Doe',
        'phone' => '0400123456',
        'email' => 'jane@example.com',
    ], (new StoreCustomerRequest)->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('email'))->toBeTrue();
});
