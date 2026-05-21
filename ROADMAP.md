# Roadmap

This document describes the **direction** of the LevantC Foundation Layer and the modular ecosystem it supports. Timelines are indicative; capabilities integrate progressively as the platform matures.

## Foundation layer (current)

**`levantc/levantc`** — stable integration targets:

| Area | Status |
|------|--------|
| Use-case and responder pipeline | Established |
| Repository and DTO abstractions | Established |
| Toast feedback and broadcasting hooks | Established |
| Console stub and module catalog traits | Established |
| Laravel provider discovery | Established |
| Platform-root test harness | Established |

Ongoing foundation work focuses on hardening extension points, documentation, and conventions—not domain product features.

## Ecosystem modules (planned)

Domain capabilities will ship as **separate modules** depending on `levantc/levantc`:

| Module | Focus |
|--------|-------|
| **Levantc\Auth** | Authentication and identity boundaries |
| **Levantc\Billing** | Billing and subscription workflows |
| **Levantc\Teams** | Team structure and collaboration context |
| **Levantc\Notifications** | Notification channels and delivery |
| **Levantc\Analytics** | Metrics, reporting, and insights |

Each module will:

- Require `levantc/levantc` as its Composer dependency root.
- Extend foundation base classes and contracts.
- Live under `modules/<name>/` in the platform monorepo via Git submodule.
- Own domain migrations, routes, configuration, and tests.

There is **no fixed release calendar** for domain modules. Integration follows operational need, consistent with [progressive integration](https://github.com/levantc/platform/blob/main/MODULES.md) in the main platform.

## Architectural direction

- **Deeper contract coverage** — Expand interfaces where modules repeatedly implement the same patterns.
- **Generator improvements** — Refine stub tokens and Artisan tooling for module scaffolding.
- **Responder enhancements** — Additional transport options only when driven by platform requirements.
- **Documentation parity** — Keep module repositories aligned with LevantC platform documentation standards.

## Non-goals

The foundation layer will **not**:

- Absorb domain business rules (auth flows, billing logic, etc.).
- Introduce an isolated Composer `vendor/` workflow for daily development.
- Replace Laravel, Inertia, or host application configuration.
- Ship as a generic Packagist framework for unrelated projects.

## Feedback

Roadmap input and prioritization discussions: **[founder.muath@levantc.io](mailto:founder.muath@levantc.io)**
