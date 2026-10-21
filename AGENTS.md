# Agent Instructions

Canonical guidance for coding agents in this repository. `.github/copilot-instructions.md` points here; keep it as a short pointer rather than duplicating these rules.

## Repository and environment

- `plugin/` contains the runtime WordPress plugin; `plugin-test/` contains development integrations and the test plugin. Root files provide build, lint, and release tooling.
- Use Node 24 from `.nvmrc`, npm, PHP 8.4, and Composer 2. All Composer platform targets and wp-env use PHP 8.4.
- Docker must be running for wp-env and Mozart dependency prefixing. Authenticated `gh` is available outside the sandbox.
- `vendor/`, `plugin/vendor/`, `plugin/vendor-prefixed/`, `plugin-test/vendor/`, `node_modules/`, compiled `dist/` output, and `archives/` are generated. Do not edit them directly or commit them unless release artifacts are explicitly requested.

## Setup and commands

```bash
npm ci
composer install
npm run build
```

Root Composer installs include both nested projects. To start WordPress, run `npm start`; its prestart hook performs locked installs, prefixes runtime dependencies, restores Composer packages for PHPUnit, and builds assets. `npm run dev` starts WordPress and watches assets; `npm run watch` only watches assets. `npm stop` stops wp-env.

| Command | Validation |
| --- | --- |
| `npm test` or `npm run lint` | PHP, JavaScript, and CSS linting; does not run PHPUnit. |
| `npm run lint:php`, `npm run lint:js`, `npm run lint:css` | Individual lint suites. |
| `npm run build` | Development assets. |
| `npm run release:build` | Production assets without version updates or packaging. |
| `npm run test:unit:env:plugin` | Plugin PHPUnit suite using the wp-env-provided runner in Docker; run after `npm start`. |

`npm run lint:fix` rewrites files; restrict fixes to the task and inspect the diff. `npm run env:clean` deletes local development and test database contents; use only when a reset is requested or necessary for an authorized test setup.

## Dependencies

- Commit manifests and corresponding lockfiles together. Prefer targeted updates; use `npm ci` and `composer install` to reproduce checked-in versions.
- Root Composer update hooks install the nested projects from their lockfiles. To update runtime or test-plugin packages, run a targeted Composer update in that project's directory explicitly.
- Runtime dependencies are declared in `plugin/composer.json` and prefixed by Mozart. After changing them, run `npm run release:dependencies` (requires Docker).
- Mozart deletes the original runtime package directories, leaving the Composer autoloader unusable until packages are restored. `npm start` restores them after prefixing. After standalone prefixing or `release:dependencies`, run `composer install --working-dir=plugin` before PHPUnit tests; `release:dependencies` also excludes development packages.
- Resolve peer incompatibilities with compatible package versions and configuration changes; do not bypass them with `--force` or `--legacy-peer-deps`.
- Do not reference prefixed dependency classes from themes, project plugins, or the test plugin. Use the plugin-owned public APIs.

## Implementation and public APIs

Follow existing WordPress coding style and project patterns. Keep changes scoped, prefer existing helpers, use ASCII for new files unless the context requires otherwise, and comment only non-obvious behavior.

[docs/public-apis.md](docs/public-apis.md) defines supported extension points, including the PHP plugin helper, settings modules, logging, SVG/icon helpers and REST routes, admin globals, and editor components. Update it when changing APIs exposed to themes, project plugins, the test plugin, JavaScript integrations, REST consumers, or settings modules. Treat unlisted internals as implementation details.

## Validation and delivery

- Run relevant lint suites and builds. Dependency changes require a clean locked install, full lint, development and production builds, and runtime checks for affected integrations.
- Run Docker-backed PHPUnit tests for PHP/runtime changes. Browser-check affected settings and editor behavior for JavaScript or dependency changes, including console errors.
- Compare failures against the base branch, fix introduced regressions, and report existing failures or unavailable checks accurately. A build alone does not prove rendered behavior works.
- Check `git status --short` before committing and stage only task-related changes. Use `codex/` branch names and Conventional Commit messages. PRs target `main` and explain resulting behavior and validation.

## GitHub and external actions

Use `gh` for PR/review work. When managing issues, inspect `blocks` / `blocked_by` relationships with `GET /repos/{owner}/{repo}/issues/{issue_number}/dependencies`. GET is read-only; changing relationships requires the appropriate GitHub write endpoint.

Do not send, forward, post, or publish external communications without explicit authorization for each individual send. Prepare content for review first; requests such as "let's reply" or "let's forward this" authorize preparation only. Ask when send authorization is ambiguous. Do not merge PRs, publish releases, or deploy changes without the required authorization. Avoid large Google Drive outputs that could cause sync problems.

## Releases

Release Please manages version/changelog PRs on `main`; the release workflow builds, uploads, and deploys artifacts after a release is created.

`npm run release` builds production assets, updates the plugin header version, prefixes dependencies, and packages ZIPs. Run it only when release artifacts or release validation are explicitly requested. Do not use the `npm version` lifecycle as a routine validation command: it starts WordPress, runs release steps, and stages all changes.
