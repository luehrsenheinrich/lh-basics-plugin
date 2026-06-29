# Agent Instructions

These instructions are the canonical guidance for coding agents working in this repository. GitHub Copilot instructions point here to avoid divergent rules.

## Project

This repository contains the LH Basics WordPress plugin and its test plugin.

- Runtime plugin code lives in `plugin/`.
- Test/integration plugin code lives in `plugin-test/`.
- Build and release tooling lives at the repository root.
- Generated dependencies and builds are not source: avoid editing `vendor/`, `plugin/vendor/`, `plugin/vendor-prefixed/`, `plugin-test/vendor/`, `node_modules/`, `archives/`, and generated dist files unless the task explicitly requires release artifacts.

## Environment

- PHP platform target: `8.4`.
- Node/npm are managed through the repo tooling.
- Docker is required for `wp-env` and for the Mozart dependency prefixing command.
- Authenticated `gh` is available outside the sandbox.

## Dependency Handling

Runtime plugin Composer dependencies are declared in `plugin/composer.json` and prefixed with Mozart into `plugin/vendor-prefixed`.

When runtime dependencies change:

```bash
composer install --working-dir=plugin --no-dev --optimize-autoloader
npm run release:dependencies
```

Do not reference prefixed dependency classes from project/theme/test-plugin code. Use the plugin-owned API surface documented in `docs/public-apis.md`.

## Common Commands

Install dependencies:

```bash
npm ci
composer install
```

Build:

```bash
npm run build
npm run release:build
```

Lint:

```bash
npm run lint
npm run lint:js
npm run lint:css
npm run lint:php
```

Fix lint issues:

```bash
npm run lint:fix
```

Run the local WordPress environment:

```bash
npm start
npm stop
npm run env:clean
```

Run tests:

```bash
npm test
npm run test:unit:env:plugin
```

`npm test` currently runs linting. `test:unit:env:plugin` requires the Docker-backed `wp-env` environment.

## Public APIs

Documented extension points live in `docs/public-apis.md`. Update that file when adding or changing APIs exposed to themes, project plugins, the test plugin, JavaScript integrations, REST consumers, or settings modules.

Current public areas include:

- `WpMunich\basics\plugin\plugin()`
- settings modules via `lhagentur_available_modules`
- logging via `plugin()->logger()`
- SVG/icon helpers and icon REST routes
- admin JavaScript globals under `window.lhSettings` and `window.lhbasics`
- block editor helper components under `window.lhbasics.components`

## Code Style

- Follow the existing WordPress coding style and project patterns.
- Keep changes scoped to the requested behavior.
- Prefer the plugin's helpers and documented APIs over reaching into internals.
- Use ASCII for new files unless existing file context requires otherwise.
- Add comments only when they clarify non-obvious behavior.

## Git and GitHub

- Use `codex/` branch names for agent-created branches unless instructed otherwise.
- Use Conventional Commit messages, for example `docs: document public plugin APIs`.
- Before committing, check `git status --short` and avoid staging unrelated user changes.
- Use `gh` for PR and review work when needed.
- When managing GitHub issues, use the GitHub REST endpoint `GET /repos/{owner}/{repo}/issues/{issue_number}/dependencies` to inspect or signal `blocks` / `blocked_by` relationships.

## Release Notes

The release command is:

```bash
npm run release
```

It builds production assets, updates the plugin version, prefixes runtime dependencies, and packages archives. Do not run it unless the task requires release artifacts or release validation.
