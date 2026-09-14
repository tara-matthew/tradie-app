<?php

use App\Models\Customer;
use Illuminate\Testing\Fluent\AssertableJson;

it('returns an empty list when there are no customers', function () {
    $this->getJson(route('api.v1.customers.index'))
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

it('returns customers ordered by newest first', function () {
    $older = Customer::factory()->create(['created_at' => now()->subDay()]);
    $newer = Customer::factory()->create(['created_at' => now()]);

    $this->getJson(route('api.v1.customers.index'))
        ->assertOk()
        ->assertJsonPath('data.0.id', $newer->id)
        ->assertJsonPath('data.1.id', $older->id);
});

it('paginates customers at fifteen per page', function () {
    Customer::factory()->count(16)->create();

    $this->getJson(route('api.v1.customers.index'))
        ->assertOk()
        ->assertJsonCount(15, 'data')
        ->assertJsonPath('meta.total', 16)
        ->assertJsonPath('meta.per_page', 15)
        ->assertJsonPath('meta.last_page', 2);
});

it('returns the expected fields for each customer without loading relations', function () {
    $customer = Customer::factory()->create();

    $this->getJson(route('api.v1.customers.index'))
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 1, fn (AssertableJson $json) => $json
                ->where('id', $customer->id)
                ->where('name', $customer->name)
                ->where('phone', $customer->phone)
                ->where('email', $customer->email)
                ->missing('jobs')
                ->missing('appointments')
                ->etc())
            ->etc());
});
