# Contributing

Report bugs, request features, or ask questions through [GitHub Issues](https://github.com/luehrsenheinrich/lh-basics-plugin/issues/new). Include reproduction steps, expected and actual behavior, and relevant WordPress/PHP versions. Do not include credentials or private customer data.

## Development setup

Follow the prerequisites and setup steps in the [README](README.md#local-development). Use Node 24, PHP 8.4, Composer 2, and Docker. `npm start` installs locked dependencies, prefixes runtime PHP packages, builds assets, and starts WordPress. `npm run watch` rebuilds assets while you work.

Runtime code belongs in `plugin/`, integration examples in `plugin-test/`, and build/tooling changes at the repository root. Documentation and configuration changes are welcome. Do not edit generated dependencies or commit build output.

Supported theme, project-plugin, settings, REST, and editor extension points are described in [docs/public-apis.md](docs/public-apis.md). Update that document when changing a public API. [AGENTS.md](AGENTS.md) contains canonical repository guidance for coding agents.

## Pull requests

1. Create a focused branch from current `main`. Coding agents use the `codex/` prefix.
2. Make scoped changes and include meaningful tests when behavior changes.
3. Run `npm test` (PHP, JavaScript, and CSS linting) and `npm run build`. For PHP/runtime changes, start wp-env and run `npm run test:unit:env:plugin`; for dependency changes also run `npm run release:build` and validate affected integrations.
4. Review `git status --short` and the diff before committing. Keep unrelated work and generated output out of the commit.
5. Use Conventional Commit messages and PR titles, such as `fix: preserve settings module selection` or `docs: clarify development setup`.
6. Open a PR targeting `main` with the problem, resulting behavior, validation results, and any limitations. Update from `main` when needed to resolve conflicts.

The `Build & Deploy` check and one approving review are currently required before merging into `main`. Passing CI does not replace review or runtime validation. `npm test` runs linting only, not PHPUnit. `npm run lint:fix` rewrites source files; review its diff before committing.

## Dependency updates

Use targeted npm or Composer updates and commit each affected manifest and lockfile together. Root Composer updates install nested projects from their lockfiles; runtime/test-plugin dependencies must be updated explicitly in their respective directories.

Runtime Composer changes require regenerating Mozart's prefixed dependencies with `npm run release:dependencies`. This removes development dependencies and the original runtime package directories; restore the test installation with `composer install --working-dir=plugin` before PHPUnit tests. Consumers should use public plugin APIs rather than prefixed classes.

## Releases

Release Please manages release PRs and changelog/version updates from Conventional Commits on `main`. After a release PR is merged, the release workflow creates the release artifacts and deploys them to the configured update server. It is not a manually dispatched version-selection workflow.

`npm run release` builds production assets, updates the plugin header version, prefixes runtime dependencies, and packages ZIPs under `archives/`. Run it only when release artifacts or release validation are explicitly requested. Coordinate publication with the project maintainer.
