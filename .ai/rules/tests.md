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
