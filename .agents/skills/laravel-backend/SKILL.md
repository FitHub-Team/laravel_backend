---
name: laravel-backend
description: Use when implementing or reviewing SuperFit Laravel API endpoints, controllers, requests, services, models, repositories, or database-related logic.
---

# Laravel Backend Engineering

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

## Backend approach
- Trace route -> middleware -> FormRequest -> controller -> service -> repository/model -> response.
- Follow existing Laravel patterns and keep business logic in services when consistent with the codebase.
- Check authentication, authorization, object ownership, validation, safe error handling, and transactions.
- Preserve API contracts, pagination, filters, response shapes, and Scramble documentation where relevant.
- Treat schema changes as proposals requiring separate approval; avoid data-destructive migrations.
- Add focused tests for success, validation failure, unauthorized access, and edge cases.
