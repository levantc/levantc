# Installation — LevantC Foundation Layer

**`levantc/levantc`** is the platform’s **foundation module**. It integrates into the LevantC Laravel application as a Git submodule and Composer dependency—not as an isolated package with its own vendor ecosystem.

## Architectural model

| Aspect | Levantc behavior |
|--------|------------------|
| Dependency resolution | Host platform `composer.json` + root `vendor/` |
| Runtime | Host Laravel application |
| Autoloading | Merged via Composer when `levantc/levantc` is required |
| Testing | Host `php artisan test` / Pest from platform root |
| Isolation | **Not** a standalone framework or micro-runtime |

## Requirements

Satisfied by the **host platform**, not by running Composer inside `modules/levantc/`:

| Requirement | Version |
|-------------|---------|
| PHP | ^8.5 |
| Laravel | 13.x |
| Composer | 2.x (platform root) |
| Inertia (for Inertia responders) | ^3.0 on host |
| Broadcasting (optional, for live toasts) | Reverb, Pusher, or compatible |

## Platform integration (primary workflow)

### 1. Initialize the submodule

From the platform repository root:

```bash
git submodule update --init --recursive modules/levantc
```

### 2. Require the foundation module in the host Composer file

The platform `composer.json` should include a path repository and require the core:

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

### 3. Install dependencies at the platform root

```bash
composer update levantc/levantc
```

This merges PSR-4 autoloading for `Levantc\` and registers Laravel service providers via package discovery.

### 4. Verify discovery and autoload

```bash
php artisan package:discover
composer dump-autoload
php -r "echo class_exists('Levantc\\Factories\\UseCaseFactory') ? 'ok' : 'missing';"
```

Expected output: `ok`.

## Configuration

Publish foundation configuration from the **host** application when using locale helpers:

```bash
php artisan vendor:publish --tag=levantc-config
```

Optional `.env` when a domain module provides the Locale model:

```env
LEVANTC_LOCALE_MODEL="Your\\Module\\Models\\Locale"
```

## Application wiring

1. Confirm `levantc/levantc` appears in `composer.lock` at the platform root.
2. Extend module controllers from `Levantc\Http\Controllers\Controller`.
3. Register **domain** module service providers in the host bootstrap—not inside Levantc.
4. For broadcast toasts, wire `Levantc\Listeners\ShowToastNotification` to `Levantc\Events\ShowToast` in the host `EventServiceProvider`.

`Levantc\Providers\BroadcastServiceProvider` registers broadcast routes when discovered; the host still owns `routes/channels.php`.

## Testing

Run tests from the **platform root** only:

```bash
php artisan test --compact --testsuite=Levantc
```

Or the full suite (includes foundation tests):

```bash
php artisan test --compact
```

Do **not** rely on `composer install` inside `modules/levantc/` for development. That directory has no independent vendor architecture by design.

## Submodule-only repository checkout

When working in the `levantc/levantc` repository alone (for example, CI on the module repo), clone the **platform** repository or use a workspace that includes the host Laravel app so tests and dependencies resolve through the parent project.

## Troubleshooting

| Symptom | Likely cause | Resolution |
|---------|--------------|------------|
| Class `Levantc\...` not found | Path repo or require missing | Add repository + `levantc/levantc`; `composer update` at platform root |
| Provider not registered | Discovery disabled | Check `extra.laravel` in module `composer.json`; run `package:discover` |
| Tests fail in module folder | Isolated test run | Run `php artisan test` from platform root |
| `LocaleHelper` error | `locale_model` unset | Publish config; set `LEVANTC_LOCALE_MODEL` |
| Inertia responder error | Inertia not on host | Ensure `inertiajs/inertia-laravel` in platform `composer.json` |

## Next steps

- [README.md](README.md) — foundation layer overview and ecosystem vision
- [CONTRIBUTING.md](CONTRIBUTING.md) — development workflow on the platform monorepo
