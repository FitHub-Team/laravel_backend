---
name: debugging
description: Use when diagnosing Laravel, React, Vite, API, build, or deployment errors in SuperFit.
---

# Systematic Debugging

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

## Debugging approach
- Reproduce or trace the failure safely using logs/output supplied by the user and relevant source code.
- State observed symptoms, likely root causes, and evidence; separate hypotheses from facts.
- Prefer the smallest targeted fix over broad refactors.
- Inspect call sites and adjacent behavior before changing signatures or response formats.
- Avoid logging secrets or personal/health data.
- After approval, add a regression test when feasible and verify the original failure is resolved.
- Explain what changed, why, and any remaining uncertainty.
