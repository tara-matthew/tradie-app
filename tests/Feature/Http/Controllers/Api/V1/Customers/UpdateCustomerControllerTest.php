<?php

use App\Models\Customer;

it('updates the customer and returns it', function () {
    $customer = Customer::factory()->create();

    $this->putJson(route('api.v1.customers.update', $customer), [
        'name' => 'Updated Name',
        'phone' => '0400999888',
        'email' => 'updated@example.com',
    ])
        ->assertOk()
        ->assertJsonPath('data.name', 'Updated Name')
        ->assertJsonPath('data.phone', '0400999888')
        ->assertJsonPath('data.email', 'updated@example.com');

    $this->assertDatabaseHas('customers', [
        'id' => $customer->id,
        'name' => 'Updated Name',
        'phone' => '0400999888',
        'email' => 'updated@example.com',
    ]);
});

it('allows keeping the same email when updating', function () {
    $customer = Customer::factory()->create(['email' => 'jane@example.com']);

    $this->putJson(route('api.v1.customers.update', $customer), [
        'name' => $customer->name,
        'phone' => $customer->phone,
        'email' => 'jane@example.com',
    ])
        ->assertOk();
});

it('fails validation when the email belongs to another customer', function () {
    Customer::factory()->create(['email' => 'taken@example.com']);
    $customer = Customer::factory()->create();

    $this->putJson(route('api.v1.customers.update', $customer), [
        'name' => $customer->name,
        'phone' => $customer->phone,
        'email' => 'taken@example.com',
    ])
        ->assertInvalid([
            'email' => 'The email has already been taken.',
        ]);
});

it('returns 404 when the customer does not exist', function () {
    $this->putJson(route('api.v1.customers.update', 999), [
        'name' => 'Someone',
        'phone' => '0400123456',
    ])
        ->assertNotFound();
});
