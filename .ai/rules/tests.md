---
paths:
  - 'tests/**/*.php'
---

# Tests

## Pest tests: use it(), chain response assertions, no intermediate variables
Write Pest tests with `it()`, not `test()`.

Chain assertions directly off the response rather than storing intermediate variables:

```php
it('returns ok', function () {
    $this->getJson(route('...'))
        ->assertOk()
        ->assertJsonCount(1, 'data');
});
```

Not:

```php
$response = $this->getJson(...);
$response->assertOk();
$response->assertJsonCount(1, 'data');
```

## Pest tests: use it(), chain response/expect assertions, no intermediate variables
Write Pest tests with `it()`, not `test()`.

Chain assertions directly off the response rather than storing intermediate variables:

```php
it('returns ok', function () {
    $this->getJson(route('...'))
        ->assertOk()
        ->assertJsonCount(1, 'data');
});
```

For `expect()`, chain multiple expectations on different values with `->and()` instead of separate `expect()` statements:

```php
expect($validator->fails())->toBeTrue()
    ->and($validator->errors()->has('email'))->toBeTrue();
```

Not:

```php
$response = $this->getJson(...);
$response->assertOk();
$response->assertJsonCount(1, 'data');

expect($validator->fails())->toBeTrue();
expect($validator->errors()->has('email'))->toBeTrue();
```
