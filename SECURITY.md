# Security Policy

## Supported versions

Security fixes are applied to actively maintained release lines of the **LevantC Foundation Layer** integrated with the LevantC Web Platform. Use the latest stable `main` branch or release tag when deploying.

| Version | Supported |
|---------|-----------|
| Latest `main` on `levantc/levantc` | Yes |
| Tagged releases pinned by the platform | Yes while actively integrated |
| Unreleased development branches | No guarantee |

Confirm the integrated version from the platform root:

```bash
composer show levantc/levantc
```

## Reporting a vulnerability

We take security seriously and appreciate responsible disclosure.

**Do not** open public GitHub issues for security vulnerabilities.

Instead, report privately to:

**[founder.muath@levantc.io](mailto:founder.muath@levantc.io)**

Include as much detail as possible:

- Description of the vulnerability and potential impact
- Steps to reproduce or proof of concept (if available)
- Affected components (foundation layer, host Silent Core, dependent modules, frontend, infrastructure)
- Platform and module version or commit hash, if known
- Your contact information for follow-up

## Responsible disclosure

- Allow reasonable time for investigation and remediation before public disclosure.
- Do not exploit vulnerabilities beyond what is needed to demonstrate the issue.
- Do not access, modify, or delete data belonging to others.
- Act in good faith; we will not pursue legal action against researchers who follow this policy.

## What to expect

1. **Acknowledgment** — We aim to confirm receipt of your report promptly.
2. **Assessment** — We will investigate severity and affected scope across the platform and dependent modules.
3. **Remediation** — A fix or mitigation plan will be developed for supported versions.
4. **Communication** — We will coordinate with you on disclosure timing when appropriate.

## Recognition

We value researchers who help keep LevantC secure. With your permission, we may acknowledge responsible disclosures in release notes or project communications.

## General security practices

When running or contributing to the foundation layer:

- Keep the platform `composer.lock` pinned in production.
- Run `composer audit` in platform CI pipelines.
- Never commit secrets (`.env`, API keys, credentials) to the repository.
- Review submodule sources before integration.
- Restrict broadcast channels appropriately when using toast events.

For non-security contact, see [Contact](CONTACT.md).
