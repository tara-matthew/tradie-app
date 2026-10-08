---
name: tradie-app-conventions
description: 'Use this skill whenever writing or extending backend code for the Tradie App API: enums under app/Enums, migrations, Eloquent models, Form Requests, API Resources, invokable controllers under app/Http/Controllers/Api/V1, or Action classes under app/Actions. Trigger when implementing a card from the "Tradie App (Backend)" Trello board or PLAN.md, adding a new endpoint/model/domain action, or when unsure how this app structures a new class. Covers: casts() vs $casts, no $fillable, typed relationship PHPDoc return hints, one-invokable-class-per-action controllers under plural resource folders, flat Form Requests, API Resources with whenLoaded(), migration style, and when a change earns a dedicated Action class vs staying inline in the controller. Does not cover test structure (see .ai/rules/tests.md) or frontend/Inertia patterns (see inertia-vue-development).'
metadata:
  author: project
---

# Tradie App Conventions

This app is a small, greenfield API (see `PLAN.md`). These are the patterns the existing code already commits to — match them exactly rather than introducing a new style, even where Laravel or another example would do it differently.

## Enums

`app/Enums/`, string-backed. Models read them via the `casts()` **method**, never a `$casts` property:

```php
protected function casts(): array
{
    return [
        'status' => AppointmentStatus::class,
    ];
}
```

## Migrations

The app has not been deployed anywhere yet, so there is no "don't edit old migrations" constraint — add new columns straight into the relevant existing migration (`database/migrations/2026_09_13_*`) instead of writing a separate alter migration.

Brand-new tables get their own migration, anonymous-class style, matching `2026_09_13_195518_create_customer_jobs_table.php`:

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CustomerJob::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            // ...columns
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
```

Foreign keys: `foreignIdFor(Model::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete()`.

## Models

No `$fillable` — global `unguard()` already handles mass assignment. `casts()` method, not `$casts` property. Relationship methods are typed and PHPDoc'd with the generic return hint, exactly like `CustomerJob`/`Customer`/`Appointment`:

```php
/**
 * @return HasMany<Quote, $this>
 */
public function quotes(): HasMany
{
    return $this->hasMany(Quote::class);
}
```

`use HasFactory;` with `/** @use HasFactory<XFactory> */` above it. Every new model gets a factory (`php artisan make:model` prompts for one, or `make:factory` separately) — don't skip it even for lookup-ish tables like `DayOverride`.

## Form Requests

Flat under `app/Http/Requests/` (not nested per-resource) — `StoreXRequest`, `UpdateXRequest`. `rules()` returns `array<string, ValidationRule|array<mixed>|string>`, matching `StoreAppointmentRequest`.

## API Resources

`app/Http/Resources/V1/`, one per model, `@mixin Model` docblock, explicit field list in `toArray()` (never a blanket `$this->toArray()`), nested resources always via `whenLoaded()`:

```php
'customer_job' => new CustomerJobResource($this->whenLoaded('customerJob')),
```

## Controllers & routes

One invokable controller per action, grouped by resource in a **plural**-named subdirectory — `Api/V1/Appointments/`, `Api/V1/Customers/`, not `Appointment/`/`Customer/`. New resource groups (jobs, quotes, time entries, messages, …) follow the same plural pattern: `Api/V1/Jobs/`, `Api/V1/TimeEntries/`, `Api/V1/Messages/`, etc.

```php
class ShowAppointmentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Appointment $appointment): AppointmentResource
    {
        return new AppointmentResource($appointment->loadMissing('customerJob.customer'));
    }
}
```

Routes live in `routes/api.php` under `Route::prefix('v1')->name('api.v1.')->group(...)`, named `{resource}.{action}` (`appointments.show`). Nested-resource creation follows `$job->appointments()->create($request->validated())` — resolve the parent via route-model binding, create directly off its relation.

## Actions (`app/Actions/`) vs. inline controller logic

No service layer. Trivial CRUD (a plain create/update off a validated request) stays directly in the controller, matching `StoreAppointmentController`. Reach for an Action class — `app/Actions/{Domain}/{VerbPhrase}.php`, single public `__invoke()` — only where there's real logic worth naming and testing in isolation: capacity rules, bucket derivation, WhatsApp sends, ETA lookups, LLM drafting, scheduled sweeps. See `PLAN.md` §4 for the full list already scoped this way (`ComputeDayCapacity`, `ResolveJobBuckets`, `SendWhatsAppMessage`, etc.).

When an Action's return value is more than a scalar, give it a small `final readonly class` DTO alongside it (e.g. `DayCapacity` next to `ComputeDayCapacity`) rather than returning an array.

## Testing

Don't restate test conventions here — see `.ai/rules/tests.md` (Pest `it()` blocks, chained assertions, no intermediate variables) and the `testing-best-practices` skill.
