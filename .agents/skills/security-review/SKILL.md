---
name: security-review
description: Use when investigating SuperFit authentication, authorization, IDOR/BOLA, sensitive data access, API exposure, and security regressions.
---

# Security Review

## Mandatory workflow
1. Inspect relevant code and dependencies before proposing changes.
2. Present a concise plan, affected files, risks, and tests. **Wait for explicit user approval before editing files.**
3. After approval, make minimal, scoped changes and preserve existing contracts and architecture.
4. Run safe, relevant checks only; report what ran and what did not.
5. Report changed files, rationale, tests, remaining risks, and next steps. Stop for review.

## Safety constraints
- Never open, print, or modify `.env` files, credentials, API keys, tokens, or secrets.
- Do not run migrations, destructive commands, production/shared database writes, deployments, push, merge, or commits without separate explicit authorization.
- Never run tests against production/shared databases. Use verified isolated test environments only.
- Preserve unrelated work and uncommitted changes; never reset or overwrite them.
- Do not change unrelated features. Ask when requirements or authorization rules are ambiguous.
- Preserve admin access-control fixes and the React/Blade toast system unless specifically asked to change them.
- SuperFit has no intended chat/direct-messaging feature. Do not add chat functionality.

## Security review approach
- Review route groups, Sanctum guards, actual principal model types, role middleware, policies, and session guards.
- Trace resource ownership and coach-trainee authorization at read/write and AI entry points.
- Examine IDOR/BOLA, mass assignment, validation scope, information leakage, and unauthorized side effects.
- Distinguish code-observed findings from proven runtime exploits.
- Rank findings by severity with file paths, line numbers, impact, and minimal mitigations.
- Do not invent business rules for pending, accepted, expired, or legacy subscriptions: request explicit decisions.
- Propose isolated authorization tests including cross-role and cross-owner cases.
- Default to investigation-only until approval.
