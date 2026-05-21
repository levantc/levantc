# LevantC Foundation Layer

**`levantc/levantc`** is the central platform core of the LevantC ecosystem—the Laravel-native foundation layer that every domain module builds upon. It provides shared architectural primitives, DDD conventions, and HTTP orchestration patterns while the Silent Core bootstraps the host application.

Levantc is **not** a separate framework or isolated Composer runtime. It extends Laravel as the **shared system kernel** inside the LevantC Web Platform.

## Documentation

- [Foundation Overview](OVERVIEW.md)
- [Architecture](ARCHITECTURE.md)
- [Installation](INSTALLATION.md)
- [Roadmap](ROADMAP.md)
- [Contributing](CONTRIBUTING.md)
- [Security Policy](SECURITY.md)
- [Code of Conduct](CODE_OF_CONDUCT.md)
- [License](LICENSE)
- [Contact](CONTACT.md)

## Role in the ecosystem

| Layer | Responsibility |
|-------|----------------|
| **Silent Core** (`app/`) | Bootstraps Laravel, coordinates modules, owns no domain business rules |
| **Levantc** (`levantc/levantc`) | Foundation layer—controllers, use cases, repositories, DTOs, responders |
| **Domain modules** | Auth, Billing, Teams, and future capabilities—depend on Levantc |

For platform-wide modular concepts, see the main repository [Modules](https://github.com/levantc/platform/blob/main/MODULES.md) documentation.

## Core capabilities

- Use-case orchestration and controller execution pipeline
- Responder strategies (JSON, Inertia, redirect-back, named routes)
- Repository and DTO abstractions for Modular DDD
- Toast feedback, broadcasting, and session flash conventions
- Console scaffolding traits for module code generation
- Laravel service provider integration via Composer discovery

## Quick start

Levantc integrates as a Git submodule at `modules/levantc` and is required from the host platform `composer.json`. Setup, verification, and testing run from the **platform repository root**—not from an isolated module `vendor/` tree.

See [Installation](INSTALLATION.md) for the full workflow.

## Standards

Contributors follow the same engineering standards as the LevantC Web Platform: PSR-12 PHP, Pest testing, Pint formatting, modular boundaries, and Conventional Commits. Foundation-layer changes must not introduce domain-specific product logic.

See [Contributing](CONTRIBUTING.md) and [Architecture](ARCHITECTURE.md).

## Contact

**[founder.muath@levantc.io](mailto:founder.muath@levantc.io)**

## License

Proprietary. See [LICENSE](LICENSE).
