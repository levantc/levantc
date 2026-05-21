# Security Policy — LevantC Foundation Layer

LevantC treats **`levantc/levantc`** as **platform-critical infrastructure**. It is the foundation layer inside the Laravel host application; a vulnerability here may affect every module that depends on it.

## Scope

This policy covers the **Levantc foundation module** (`modules/levantc`, package `levantc/levantc`). Domain modules (Auth, Billing, Teams, and others) may have separate policies as they mature.

## Supported versions

| Version | Supported |
|---------|-----------|
| Latest `main` integrated with the platform | Yes |
| Tagged releases on the module repository | Yes while platform pins them |
| Unreleased development branches | No guarantee |

Confirm the integrated version from the **platform** root:

```bash
composer show levantc/levantc
```

## Reporting a vulnerability

**Do not** open public GitHub issues for security-sensitive findings.

Report privately to:

**[founder.muath@levantc.io](mailto:founder.muath@levantc.io)**

Include:

1. Description and impact on the host Laravel application or dependent modules
2. Reproduction steps or proof of concept
3. Platform and module commit or tag references
4. Suggested remediation (optional)
5. Your contact information for follow-up

## Responsible disclosure

- Allow reasonable time for investigation and patching before public disclosure
- Do not access or modify third-party data without authorization
- Comply with applicable laws

**Acknowledgment target:** 5 business days  
**Initial assessment target:** 15 business days

## Integrator best practices

Because Levantc shares the host `vendor/` graph:

- Keep the platform `composer.lock` pinned in production
- Run `composer audit` in platform CI
- Patch PHP and Laravel on the host application
- Review broadcast channel configuration before exposing toast events
- Never commit `.env` or secrets to the module repository

## Out of scope

- Misconfiguration limited to the host application
- Vulnerabilities in optional domain modules that do not originate in Levantc foundation APIs
- Issues requiring local server access only

When uncertain, report anyway—we will clarify scope during triage.

For non-security contact, see [CONTACT.md](CONTACT.md).
