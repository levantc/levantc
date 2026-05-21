# Architecture

The LevantC Foundation Layer is designed for **modular scalability**, **Laravel-native integration**, and **strict separation** between shared infrastructure and domain-specific business logic.

## Foundation layer model

Levantc operates **on top of** the Laravel application architecture. It is the first module in the LevantC ecosystem and the **dependency root** for all modules that follow.

```bash
.
├── Host Laravel Application (LevantC Web Platform)
│   ├── vendor/                    # Single Composer dependency graph
│   ├── app/                       # Silent Core — orchestration only
│   └── modules/
│       ├── levantc/               # Foundation Layer (this repository)
│       │   ├── config/
│       │   ├── src/               # Levantc\ namespace
│       │   └── tests/
│       └── <domain-module>/       # Future: Auth, Billing, Teams, …
```

The foundation layer does **not** simulate a framework-inside-a-framework. Service providers register with Laravel; factories resolve through the application container; tests run against the full host application.

## Modular Domain Driven Design

Within Levantc and extending domain modules, domain logic follows consistent layers:

| Layer | Responsibility |
|-------|----------------|
| **DTOs** | Structured data transfer; explicit input and output shapes |
| **Repositories** | Persistence abstraction; Eloquent mapping to DTOs |
| **Services / Use cases** | Business orchestration and application workflows |
| **Contracts** | Interfaces for abstraction and testability |
| **Enums** | Domain and transport constants |
| **Http** | Controllers, responders, and request/response options |
| **Responses** | Standardized use-case outcome envelopes |
| **Actions** | User-facing feedback descriptors (toast, actions) |

### Request flow

```text
HTTP Request
    → Controller (Levantc\Http\Controllers\Controller)
        → UseCaseFactory → UseCase::handle(DTO)
            → Response (data, toast, result semantics)
        → ResponderFactory → Responder (JSON | Inertia | Redirect)
    → HTTP Response
```

Business logic never selects transport format. Responders translate `Response` objects into the appropriate Laravel or Inertia output.

## Foundation file structure

```bash
modules/levantc/
├── config/
│   └── levantc.php              # Publishable host configuration
├── src/
│   ├── Actions/                 # Feedback and action descriptors
│   ├── Console/                 # Base Artisan command for module tooling
│   ├── Contracts/               # Shared interfaces
│   ├── DTOs/                    # Data transfer object bases
│   ├── Enums/                   # Typed constants
│   ├── Events/                  # Broadcast-friendly events
│   ├── Factories/               # UseCase, Repository, Responder factories
│   ├── Helpers/                 # Cross-cutting helpers (when configured)
│   ├── Http/
│   │   ├── Controllers/         # Foundation controller base
│   │   └── Responders/          # JSON, Inertia, redirect strategies
│   ├── Listeners/               # Framework event listeners
│   ├── Model/                   # Eloquent base models for modules
│   ├── Providers/               # ModuleServiceProvider, BroadcastServiceProvider
│   ├── Repositories/            # Repository abstractions
│   ├── Responses/               # Use-case response envelope
│   ├── Services/                # Service and UseCase bases
│   └── Traits/                  # Console, stub, transaction concerns
├── tests/                       # Pest suite (host platform harness)
└── composer.json                # levantc/levantc — autoload and discovery
```

## Integration with the Silent Core

| Concern | Silent Core | Foundation Layer |
|---------|-------------|------------------|
| Business logic | None (orchestration only) | Shared patterns only—no product domains |
| Composer dependencies | Full Laravel stack | `php` + autoload metadata |
| Service providers | App-level registration | Auto-discovered from `levantc/levantc` |
| Routes | System routes | None—domain modules own routes |
| Testing | Platform suites | `Levantc` testsuite via host `phpunit.xml` |

Adding domain modules should not require refactoring Levantc, provided modules honor the integration contract: extend foundation types, register domain providers in the host, and keep domain code out of `Levantc\` core namespaces.

## Composer and autoloading

Package name: **`levantc/levantc`**

- PSR-4: `Levantc\` → `src/`
- Laravel `extra.laravel.providers` for service provider discovery
- Host platform requires the package via path repository (`modules/levantc`)

Runtime dependencies (Laravel, Inertia, broadcasting) are satisfied by the **host application**, maintaining a single `vendor/` graph across the platform.

## Configuration

Publish foundation configuration from the host when needed:

```bash
php artisan vendor:publish --tag=levantc-config
```

Optional locale model binding for `LocaleHelper` when internationalization is provided by another module:

```env
LEVANTC_LOCALE_MODEL="Your\\Module\\Models\\Locale"
```

## Scalability principles

- **Horizontal module growth** — New domains add modules, not foundation bloat.
- **Independent repositories** — Foundation and domain modules version separately via submodules.
- **Testability** — Pest validates contracts through the platform application shell.
- **Stable extension points** — Factories, responders, and base classes evolve with semver discipline.

For ecosystem direction, see [Roadmap](ROADMAP.md). For integration steps, see [Installation](INSTALLATION.md).
