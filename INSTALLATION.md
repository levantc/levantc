# Installation

This guide covers integrating the **LevantC Foundation Layer** (`levantc/levantc`) into the LevantC Web Platform for development or testing.

The foundation module is **not** installed as an isolated Composer package with its own `vendor/` tree. It integrates into the host Laravel application through a Git submodule and the platform root `composer.json`.

## Prerequisites

Satisfied by the **host platform** environment:

| Requirement | Version / notes |
|-------------|-----------------|
| **PHP** | >= 8.5 (`pdo_pgsql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`) |
| **Composer** | 2.x — run at platform repository root |
| **Laravel** | 13.x |
| **PostgreSQL** | Required by the LevantC Web Platform |
| **Git** | Submodule management |
| **Node.js** | LTS — for platform frontend tooling when running full stack |

Optional on the host for full foundation features:

- **inertiajs/inertia-laravel** ^3.0 — Inertia responders
- **Broadcasting** — Reverb or compatible driver for live toast events

For full platform setup, see the main repository [Installation](https://github.com/levantc/platform/blob/main/INSTALLATION.md).

## 1. Clone the platform repository

```bash
git clone git@github.com:<username>/<levantc-platform-repo>.git
cd <levantc-platform-repo>
```

Replace `<username>` and `<levantc-platform-repo>` with your GitHub username and platform repository name.

## 2. Initialize the foundation submodule

```bash
git submodule update --init --recursive modules/levantc
```

Verify `.gitmodules` lists the foundation module:

```bash
cat .gitmodules
```

Expected path: `modules/levantc` → `git@github.com:levantc/levantc.git`

> For the complete submodule workflow and progressive module integration, see [Modules](https://github.com/levantc/platform/blob/main/MODULES.md).

## 3. Require the foundation package

Ensure the platform `composer.json` includes a path repository and requires the foundation layer:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "modules/levantc",
            "options": { "symlink": true }
        }
    ],
    "require": {
        "levantc/levantc": "@dev"
    }
}
```

## 4. Install backend dependencies

From the **platform repository root**:

```bash
composer update levantc/levantc
```

## 5. Verify package discovery and autoload

```bash
php artisan package:discover
composer dump-autoload
php -r "echo class_exists('Levantc\\Factories\\UseCaseFactory') ? 'ok' : 'missing';"
```

Expected output: `ok`.

Confirm discovery:

```bash
composer show levantc/levantc
```

## 6. Environment configuration

Publish foundation configuration when using locale helpers or overrides:

```bash
php artisan vendor:publish --tag=levantc-config
```

Optional `.env` entry when a domain module provides the Locale model:

```env
LEVANTC_LOCALE_MODEL="Your\\Module\\Models\\Locale"
```

Configure remaining variables per the platform `.env.example` (database, mail, broadcasting, and module-specific keys).

## 7. Application wiring

1. Extend domain controllers from `Levantc\Http\Controllers\Controller`.
2. Register **domain** module service providers in the host bootstrap—not inside the foundation layer.
3. For broadcast toasts, register `Levantc\Listeners\ShowToastNotification` for `Levantc\Events\ShowToast` in the host `EventServiceProvider`.
4. Ensure `routes/channels.php` exists on the host when using `BroadcastServiceProvider`.

## 8. Testing

Run foundation tests from the **platform root**:

```bash
php artisan test --compact --testsuite=Levantc
```

Run the full platform suite when validating broader integration:

```bash
php artisan test --compact
```

> **Note:** Do not run `composer install` inside `modules/levantc/` for routine development. Dependencies resolve through the host application.

## Verification

- `levantc/levantc` appears in `composer.lock` at the platform root.
- `php artisan package:discover` lists `levantc/levantc`.
- Foundation Pest tests pass via `--testsuite=Levantc`.

## Troubleshooting

| Symptom | Likely cause | Resolution |
|---------|--------------|------------|
| Class `Levantc\...` not found | Path repo or require missing | Add repository and `levantc/levantc`; `composer update` at platform root |
| Provider not registered | Discovery issue | Run `php artisan package:discover`; verify `extra.laravel` in module `composer.json` |
| Tests fail in module directory | Isolated test execution | Run tests from platform root only |
| `LocaleHelper` runtime error | `locale_model` unset | Publish config; set `LEVANTC_LOCALE_MODEL` |
| Inertia responder error | Inertia not on host | Require `inertiajs/inertia-laravel` in platform `composer.json` |

## Related documentation

- [Foundation Overview](OVERVIEW.md)
- [Architecture](ARCHITECTURE.md)
- [Contributing](CONTRIBUTING.md)
