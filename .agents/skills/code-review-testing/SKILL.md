---
name: code-review-testing
description: Use when reviewing diffs, verifying Codex changes, checking regressions, and preparing SuperFit work for a commit.
---

# Code Review and Testing

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

## Review approach
- Inspect `git status` and the scoped diff without discarding unrelated changes.
- Review correctness, security, performance, accessibility, architecture, and backward compatibility.
- Check tests for meaningful assertions, negative cases, and hidden environment assumptions.
- Run syntax, lint, targeted tests, and builds only where safe and supported.
- Do not claim browser end-to-end or full-suite verification unless actually performed.
- Report findings first by severity, with precise paths/lines and actionable fixes.
- Never commit, push, or merge as part of review unless separately authorized.
