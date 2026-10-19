# L//H Basics Plugin

[![Build & Deploy](https://github.com/luehrsenheinrich/lh-basics-plugin/actions/workflows/main.yml/badge.svg)](https://github.com/luehrsenheinrich/lh-basics-plugin/actions/workflows/main.yml)

Shared WordPress functionality for projects by [Luehrsen // Heinrich](https://www.luehrsen-heinrich.de). The plugin provides configurable site features and reusable integrations for themes, project plugins, and the block editor.

## Features

Enable modules under **Settings > Luehrsen // Heinrich**:

- Application password controls and site-wide comment disabling.
- Image lazy loading with lazysizes and a media lightbox with GLightbox.
- Gravity Forms integration when Gravity Forms is installed.
- CSS variable helpers for theme color palettes.
- A log viewer with configurable severity.

Developers can extend settings modules, use the plugin logger and SVG/icon helpers, and reuse admin and block editor components. See the [public APIs and extension points](docs/public-apis.md) for supported integrations.

## Install a release

1. Download the plugin ZIP (`lhbasicsp.zip`) from [GitHub Releases](https://github.com/luehrsenheinrich/lh-basics-plugin/releases).
2. In WordPress, choose **Plugins > Add New Plugin > Upload Plugin**, upload the ZIP, and activate **L//H Basics**.
3. Configure the modules under **Settings > Luehrsen // Heinrich**.

Use PHP 8.4, the runtime dependency platform target. Release ZIPs include compiled assets and prefixed PHP dependencies. GitHub's automatic source archives require the development build steps below. The test plugin is for local development only.

## Local development

Prerequisites: Node.js 24 with npm, PHP 8.4 with Composer 2, Git, and a running Docker installation. [nvm](https://github.com/nvm-sh/nvm) users can run `nvm install` and `nvm use` to select the version in `.nvmrc`.

```bash
git clone https://github.com/luehrsenheinrich/lh-basics-plugin.git
cd lh-basics-plugin
npm start
```

`npm start` first installs the npm and all three Composer projects from their lockfiles, generates prefixed runtime dependencies with Docker/Mozart, restores Composer dependencies needed by PHPUnit, builds assets, and starts the Docker-backed WordPress environment. Both `plugin/` and `plugin-test/` are mounted.

Open [http://localhost](http://localhost) and log in with the local development account `admin` / `password`. The test site uses wp-env's default port 8889. If port 80 is occupied, create an untracked `.wp-env.override.json` with an alternative development port, for example:

```json
{ "env": { "development": { "port": 8888 } } }
```

For an asset-only checkout without starting WordPress:

```bash
npm ci
composer install
npm run build
```

Before loading the plugin in WordPress, generate its runtime dependencies with `composer run --working-dir=plugin prefix-dependencies` (requires Docker). Mozart removes the original runtime package directories; run `composer install --working-dir=plugin` afterward to restore the Composer autoloader used by PHPUnit.

## Commands

| Command | Purpose |
| --- | --- |
| `npm start` | Install locked dependencies, prefix runtime dependencies, build, and start WordPress. |
| `npm stop` | Stop the local WordPress environment. |
| `npm run watch` | Rebuild assets as source files change. |
| `npm run dev` | Start WordPress and then watch assets. |
| `npm run build` | Build development assets. |
| `npm run release:build` | Build production assets without changing the version or packaging a release. |
| `npm test` / `npm run lint` | Run PHP, JavaScript, and CSS linting; these commands do not run PHPUnit. |
| `npm run lint:php` / `lint:js` / `lint:css` | Run one lint suite. |
| `npm run lint:fix` | Rewrite files using the lint autofixers; review the resulting diff. |
| `npm run test:unit:env:plugin` | Run plugin PHPUnit tests in wp-env's tests container after `npm start`. |
| `npm run env:clean` | Reset development and test databases; deletes local site data. |

## Repository and dependencies

- `plugin/`: runtime plugin, frontend assets, and admin/editor integrations.
- `plugin-test/`: local integration examples and test plugin.
- `docs/public-apis.md`: supported extension points.
- Repository root: build, lint, environment, and release tooling.

Root Composer dependencies provide development tools. Runtime dependencies belong to `plugin/composer.json`; Mozart generates `plugin/vendor-prefixed/`. The test plugin has its own Composer project. Root Composer installs and updates install the nested projects from their lockfiles; update a nested dependency explicitly in its own directory.

Use the documented plugin APIs rather than prefixed dependency classes in external integrations. Dependency directories, compiled assets, and release archives are generated and should not be committed.

## Troubleshooting

- **Docker or prefixing fails:** start Docker and retry `npm start`. To regenerate runtime dependencies directly, run `npm run release:dependencies`; this installs runtime packages without development dependencies and removes the original runtime package directories. Run `composer install --working-dir=plugin` again before PHPUnit tests.
- **Missing assets or a blank settings page:** run `npm run build`, confirm that runtime dependencies have been prefixed, and inspect the PHP and browser error logs.
- **Dependency installation fails:** check Node 24 and PHP 8.4, use the checked-in lockfiles, and resolve peer conflicts rather than using `--force` or `--legacy-peer-deps`.
- **Local port conflict:** use `.wp-env.override.json` as shown above and update the URL you open.

## Contributing and releases

See [CONTRIBUTING.md](CONTRIBUTING.md) for the development workflow and [AGENTS.md](AGENTS.md) for canonical coding-agent guidance.

Release Please opens version/changelog PRs from Conventional Commits on `main`. When its release PR is merged and a release is created, the release workflow builds and packages the plugin, attaches ZIPs to the GitHub release, and deploys them to the configured update server. `npm run release` locally also builds production assets, updates the plugin header version, prefixes runtime dependencies, and packages archives; use it only for an explicitly requested release or release validation.

See [CHANGELOG.md](CHANGELOG.md) and [GitHub Releases](https://github.com/luehrsenheinrich/lh-basics-plugin/releases) for release history.

## License

[GNU General Public License v2 or later](LICENSE).
