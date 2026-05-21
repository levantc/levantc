# Foundation Overview

The **LevantC Foundation Layer** (`levantc/levantc`) is the shared system kernel of the LevantC modular platform. It encodes the architectural contracts, response patterns, and domain primitives that every LevantC module inherits—without owning product-specific business rules.

## Vision

LevantC envisions a collaborative technical ecosystem where independent professionals deliver structured digital initiatives with clarity, autonomy, and long-term maintainability. The foundation layer ensures that vision scales: new domain modules arrive with **consistent patterns**, not duplicated infrastructure.

## Purpose

The foundation layer exists to:

- Provide the **central dependency root** for all LevantC domain modules.
- Standardize Modular DDD structure (DTOs, repositories, services, use cases, responders).
- Keep business logic out of the Silent Core while preserving Laravel-native integration.
- Enable progressive ecosystem growth—Auth, Billing, Teams, Notifications, Analytics—without refactoring the platform spine.
- Deliver explicit, testable outcomes through typed responses and feedback models.

## Position in the platform

LevantC engineering organizes delivery around three cooperating layers:

| Layer | Location | Role |
|-------|----------|------|
| **Silent Core** | `app/`, `bootstrap/`, `config/`, core `routes/` | Orchestration, bootstrapping, module coordination |
| **Foundation Layer** | `modules/levantc` → `Levantc\` | Shared kernel—patterns all modules extend |
| **Domain modules** | `modules/<domain>/` | Bounded contexts with full business rules |

The Silent Core bootstraps Laravel and wires cross-cutting concerns. **Levantc** supplies what modules need to stay architecturally aligned. Domain modules own their models, migrations, routes, and product logic.

## Collaboration with domain modules

Domain modules are developed in **separate repositories** and integrated via Git submodules when a platform environment requires them. Each module:

1. Declares a dependency on **`levantc/levantc`** in Composer.
2. Extends foundation base types (`Controller`, `UseCase`, `ModelRepository`, and related contracts).
3. Registers its own service providers in the host application.
4. Keeps namespaces distinct (for example `Levantc\Auth\`, not mixed into `Levantc\` core).

This preserves independent lifecycles, versioned releases, and reduced blast radius for change.

## Technology alignment

The foundation layer aligns with the LevantC Web Platform stack:

| Concern | Alignment |
|---------|-----------|
| PHP | ^8.5 (host application) |
| Laravel | 13.x (host `vendor/` graph) |
| Frontend bridge | Inertia responders when host uses Inertia v3 |
| Real-time feedback | Broadcasting via host configuration (Reverb, etc.) |
| Testing | Pest through the platform test harness |

Dependencies resolve through the **host platform** `composer.json`. The module `composer.json` declares identity, autoloading, and Laravel discovery—not a duplicate framework stack.

## Design principles

- **Laravel-native** — Extend Laravel; do not replace it.
- **Foundation-first** — Shared infrastructure only; no domain product rules.
- **Explicit outcomes** — `Response` envelopes with data, toasts, and result semantics.
- **Transport independence** — Responders select JSON, Inertia, or redirects; use cases stay unaware.
- **Progressive integration** — Modules join the platform as requirements emerge.

For technical structure and file layout, see [Architecture](ARCHITECTURE.md). For setup, see [Installation](INSTALLATION.md). For planned modules, see [Roadmap](ROADMAP.md).
