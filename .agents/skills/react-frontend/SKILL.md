---
name: react-frontend
description: Use when building or reviewing SuperFit React pages, components, hooks, services, forms, and API integration.
---

# React Frontend Engineering

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

## Frontend approach
- Inspect the existing React/Vite/Tailwind structure and reuse components, hooks, and services.
- Preserve the current green/white theme, Arabic language, RTL layout, and responsive behavior.
- Integrate APIs through existing service patterns; do not hardcode demo data in production UI.
- Handle loading, empty, error, and success states; use the existing shared toast system without duplicate notifications.
- Keep inline form validation visible and avoid exposing raw backend errors.
- Preserve existing routes, accessibility, and state-management conventions.
- Verify build/lint/tests when safely available.
