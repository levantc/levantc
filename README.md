# LevantC — Foundation Layer

**`levantc/levantc`** is the **central platform core** of the LevantC ecosystem: the Laravel-native foundation layer that every future module builds upon. It is not a separate framework, not an isolated Composer runtime, and not a replacement for Laravel—it is the **shared system kernel** integrated into the host application.

## Position in the platform

```
┌─────────────────────────────────────────────────────────┐
│  Laravel Application (Silent Core host)                 │
│  vendor/ — single dependency graph                      │
├─────────────────────────────────────────────────────────┤
│  Levantc (Foundation Layer)  ← levantc/levantc          │
│  Shared infrastructure · DDD primitives · responders    │
├─────────────────────────────────────────────────────────┤
│  Future modules (depend on Levantc)                   │
│  Levantc\Auth · Levantc.Billing · Levantc.Teams · …   │
└─────────────────────────────────────────────────────────┘
```

| Role | Description |
|------|-------------|
| **Platform Core** | Central dependency root for the modular architecture |
| **Foundation Layer** | Base controllers, use cases, repositories, DTOs, responders |
| **Shared Infrastructure** | Factories, events, console traits, configuration contracts |
| **Ecosystem Foundation** | Conventions future modules inherit—not reimplement |

The Silent Core bootstraps Laravel and wires modules. **Levantc** supplies the architectural spine domain modules share.

## What Levantc provides

- **Use-case pipeline** — orchestration from HTTP controllers through `UseCase` execution
- **Responder system** — JSON, Inertia, redirect-back, and named-route strategies using host Laravel + Inertia
- **Repository layer** — Eloquent-backed `ModelRepository` with DTO mapping and snapshots
- **Feedback model** — `Toast`, broadcasting, session flash, and action descriptors
- **Console foundations** — module catalog selection, stub token builders, CLI UX traits
- **Laravel integration** — service providers discovered by the host application

## Architecture philosophy

LevantC treats the platform as **modular bounded contexts on Laravel**:

| Principle | How Levantc applies it |
|-----------|------------------------|
| **Laravel-native** | Extends Laravel; uses the host `vendor/` dependency graph |
| **Foundation-first** | No parallel framework; no isolated package runtime |
| **DDD layers** | Controllers → Use Cases → Services/Repositories → Models/DTOs |
| **Explicit outcomes** | `Response` envelopes carry data, toasts, and result semantics |
| **Transport independence** | Responders choose JSON/Inertia/redirect—not business logic |
| **Scalable modules** | Domain code lives in separate modules that **require** `levantc/levantc` |

## Technical identity

- Root namespace: **`Levantc\`**
- Source: `src/` (PSR-4)
- PHP **8.5+** on the host application
- Laravel **13** and ecosystem packages resolved by the **platform** `composer.json`

## Module structure

```
modules/levantc/
├── config/levantc.php       Host-publishable configuration
├── src/                     Levantc\ — foundation layer code
│   ├── Actions/             Feedback and action descriptors
│   ├── Console/             Base Artisan command for module tooling
│   ├── Contracts/           Shared interfaces
│   ├── DTOs/                Data transfer objects
│   ├── Enums/               Typed constants
│   ├── Events/              Broadcast-friendly events
│   ├── Factories/           Container-backed factories
│   ├── Helpers/             Cross-cutting helpers (when configured)
│   ├── Http/                Controllers and responders
│   ├── Listeners/           Event listeners
│   ├── Model/               Eloquent base models for modules
│   ├── Providers/           Laravel service providers
│   ├── Repositories/        Repository abstractions
│   ├── Responses/           Use-case response envelope
│   ├── Services/            Service and use-case bases
│   └── Traits/              Console, stub, and transaction concerns
└── tests/                   Run via host platform test suite
```

## Usage in a module

Future modules (Auth, Billing, Teams, and others) declare a dependency on **`levantc/levantc`** and extend foundation types:

```php
use Levantc\Http\Controllers\Controller;
use Levantc\Enums\Responders\ResponderType;

class StoreTeamController extends Controller
{
    public function __invoke(StoreTeamRequest $request)
    {
        return $this->execute(
            serviceClass: StoreTeam::class,
            data: StoreTeamDTO::fromRequest($request),
            responderType: ResponderType::INERTIA,
            component: 'Teams/Index',
        );
    }
}
```

## Ecosystem vision

Planned modules extend the foundation without duplicating it:

```text
Levantc\Auth
Levantc\Billing
Levantc\Teams
Levantc\Notifications
Levantc\Analytics
```

Each depends on **`Levantc\Levantc`** (package: `levantc/levantc`) as the central dependency root.

## Integration model

Levantc is integrated as a **Git submodule** at `modules/levantc` and required from the **platform** `composer.json`. Composer merges autoloading; Laravel discovers providers; tests execute from the **host application root**—there is no separate `vendor/` tree inside this module for day-to-day development.

See [INSTALLATION.md](INSTALLATION.md) for setup.

## Documentation

| Document | Description |
|----------|-------------|
| [INSTALLATION.md](INSTALLATION.md) | Submodule and platform Composer integration |
| [CONTRIBUTING.md](CONTRIBUTING.md) | Contribution workflow |
| [SECURITY.md](SECURITY.md) | Vulnerability reporting |
| [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md) | Community standards |
| [CONTACT.md](CONTACT.md) | Official contact |

## Contact

**[founder.muath@levantc.io](mailto:founder.muath@levantc.io)** — support, security, partnerships, and conduct inquiries.

## License

Proprietary — © LevantC. All rights reserved.
