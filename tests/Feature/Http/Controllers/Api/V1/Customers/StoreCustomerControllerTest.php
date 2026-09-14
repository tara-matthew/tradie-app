<?php

it('creates a customer and returns it', function () {
    $this->postJson(route('api.v1.customers.store'), [
        'name' => 'Jane Doe',
        'phone' => '0400123456',
        'email' => 'jane@example.com',
    ])
        ->assertCreated()
        ->assertJsonPath('data.name', 'Jane Doe')
        ->assertJsonPath('data.phone', '0400123456')
        ->assertJsonPath('data.email', 'jane@example.com');

    $this->assertDatabaseHas('customers', [
        'name' => 'Jane Doe',
        'phone' => '0400123456',
        'email' => 'jane@example.com',
    ]);
});

it('creates a customer without an email', function () {
    $this->postJson(route('api.v1.customers.store'), [
        'name' => 'Jane Doe',
        'phone' => '0400123456',
    ])
        ->assertCreated()
        ->assertJsonPath('data.email', null);
});

it('fails validation when required fields are missing', function () {
    $this->postJson(route('api.v1.customers.store'), [])
        ->assertInvalid([
            'name' => 'The name field is required.',
            'phone' => 'The phone field is required.',
        ]);
});
