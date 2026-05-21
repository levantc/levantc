# Contributing — LevantC Foundation Layer

Contributions to **`levantc/levantc`** shape the **central dependency root** of the LevantC modular platform. Changes must preserve Laravel-native integration, foundation-layer boundaries, and stability for all future modules.

## What you are contributing to

Levantc is:

- The **platform core** module—not a generic Packagist library
- The **foundation layer** future modules (`Levantc\Auth`, `Levantc\Billing`, …) depend on
- A **shared system kernel** loaded by the host Laravel application
- **Not** a framework replacement or isolated runtime

Develop against the **platform monorepo** with the submodule checked out at `modules/levantc`.

## Before you start

1. Read [README.md](README.md) for architectural positioning.
2. Search existing issues and pull requests.
3. For significant changes, discuss scope before large refactors.

## Branching strategy

| Branch | Purpose |
|--------|---------|
| `main` | Stable foundation integration |
| `feature/<description>` | New shared infrastructure |
| `fix/<description>` | Bug fixes |
| `chore/<description>` | Docs, tooling |

Rebase onto latest `main` before opening a pull request.

## Pull request workflow

1. Branch from `main` in the **levantc** repository (submodule).
2. Implement focused changes with tests in `tests/`.
3. From the **platform root**, run:
   ```bash
   php artisan test --compact --testsuite=Levantc
   vendor/bin/pint modules/levantc
   ```
4. Open a PR describing **why**, breaking changes, and test plan.
5. After merge, update the platform submodule pointer when integrating.

## Coding standards

- **PHP 8.5+** with explicit types and readonly value objects where appropriate
- **PSR-4** — all production code under `Levantc\` in `src/`
- **Laravel conventions** — providers, container, Eloquent alignment with the host
- **Foundation-only** — no Auth, Billing, or other domain logic in this module
- **No isolated package patterns** — avoid Testbench-only assumptions, duplicate framework abstractions, or module-local `vendor/` workflows
- **PHPDoc** for non-obvious contracts; avoid noise comments

## Architecture consistency

| Layer | Rule |
|-------|------|
| Controllers | Delegate to use cases; no business rules |
| Use cases | Return `Levantc\Responses\Response` (or typed subclasses) |
| Repositories | Map models to DTOs; do not leak Eloquent past repository boundaries |
| Responders | Translate responses to HTTP/Inertia/JSON |
| New contracts | Place in `src/Contracts/`; no imports from future domain modules |

## Testing expectations

- Tests live in `modules/levantc/tests/`
- Execute from the **host platform** test harness
- Every behavioral change includes or updates Pest tests
- Do not delete tests without maintainer approval

```bash
# From platform repository root
php artisan test --compact --testsuite=Levantc
```

## Composer and dependencies

- **`modules/levantc/composer.json`** declares identity, autoload, and Laravel discovery—not a full duplicate of the framework stack
- Add new **runtime** dependencies to the **platform** `composer.json` when the host application needs them
- Keep `levantc/levantc` `require` minimal (`php` only); Laravel packages belong on the host

## Documentation

Update README or INSTALLATION when integration steps, structure, or ecosystem positioning changes.

## Commit conventions

Use clear imperative subjects:

```
Add responder option for external redirect URLs
Document platform-root test workflow in INSTALLATION
```

Reference issues when applicable: `Fixes #42`.

## Questions

Email **[founder.muath@levantc.io](mailto:founder.muath@levantc.io)** or see [CONTACT.md](CONTACT.md).

For security vulnerabilities, follow [SECURITY.md](SECURITY.md) — do not open public issues.
