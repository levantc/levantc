# Contributing

Thank you for contributing to the **LevantC Foundation Layer** (`levantc/levantc`). Following this guide keeps the ecosystem consistent, testable, and aligned with the LevantC Web Platform engineering standards.

## Scope of this repository

Contributions here affect the **shared system kernel**—not domain product features. Auth, Billing, Teams, and similar capabilities belong in future domain module repositories.

Before opening a PR, read [Foundation Overview](OVERVIEW.md) and [Architecture](ARCHITECTURE.md).

## Branch strategy

- **`main`** — Stable foundation integration branch.
- **Feature branches** — Create from `main` with descriptive names (e.g. `feat/responder-options`, `fix/use-case-factory-binding`).

Workflow:

1. Fork or branch from `main` in the **levantc** repository.
2. Implement and test against the **platform monorepo** (submodule checkout).
3. Push and open a PR with a clear description and test plan.
4. After merge, update the platform submodule pointer when integrating into the main application.

> Platform-wide contributions targeting the Silent Core follow the main repository [Contributing](https://github.com/levantc/platform/blob/main/CONTRIBUTING.md) guide (`develop` branch workflow).

## Pull request workflow

1. **Scope your work** — One logical change per PR; respect foundation-layer boundaries.
2. **Test before opening** — See [Testing requirements](#testing-requirements).
3. **Write a clear PR description** — What changed, why, and how to verify. Note impact on downstream modules.
4. **Respond to review** — Address feedback with focused follow-up commits.

## Commit convention

Use [Conventional Commits](https://www.conventionalcommits.org/) style:

```text
type(scope): Short and clear description
```

### Types

| Type | Use for |
|------|---------|
| `feat` | New foundation capability or extension point |
| `fix` | Bug fixes in shared infrastructure |
| `refactor` | Restructuring without behavior change |
| `docs` | Documentation only |
| `test` | Adding or updating tests |
| `chore` | Maintenance, tooling, non-behavior config |

### Examples

```text
feat(responders): add query parameter support to redirect options
fix(providers): correct UseCaseFactory singleton registration
docs(installation): align submodule steps with platform guide
refactor(dto): simplify null filtering contract
```

## Testing requirements before PR

All PRs must include passing tests for affected behavior. From the **platform repository root**:

```bash
php artisan test --compact --testsuite=Levantc
```

Format changed PHP:

```bash
vendor/bin/pint modules/levantc
```

Guidelines:

- Add or update **Pest** tests under `modules/levantc/tests/`.
- Tests run through the host Laravel application—not an isolated package runtime.
- Use existing conventions; avoid brittle assertions.
- Do not delete tests without maintainer approval.

## Code style and quality

- **PHP** — Follow [PSR-12](https://www.php-fig.org/psr/psr-12/). Run Pint on changed files.
- **PSR-4** — Production code under `Levantc\` in `src/`.
- **Modularity** — No domain product logic in the foundation layer.
- **Laravel alignment** — Prefer framework conventions over parallel abstractions.
- **Documentation** — Update relevant docs when integration, structure, or public APIs change.

## Architecture consistency

| Layer | Rule |
|-------|------|
| Controllers | Delegate to use cases; no business rules |
| Use cases | Return `Levantc\Responses\Response` (or typed subclasses) |
| Repositories | Map Eloquent to DTOs; do not leak models past repository boundaries |
| Responders | Translate responses to HTTP/Inertia/JSON |
| Contracts | New shared interfaces live in `src/Contracts/` |

## Reporting issues

1. **Search** existing issues for duplicates.
2. **Title** — Clear, specific summary.
3. **Body** — Steps to reproduce, expected vs. actual behavior, PHP/Laravel versions, `composer show levantc/levantc` output.
4. **Labels** — Use `bug`, `enhancement`, or `question` when available.

## Security

Do not report security vulnerabilities in public issues. See [Security Policy](SECURITY.md).

## Code of conduct

All participants must follow the [Code of Conduct](CODE_OF_CONDUCT.md).

## Questions

**[founder.muath@levantc.io](mailto:founder.muath@levantc.io)** — or see [Contact](CONTACT.md).
