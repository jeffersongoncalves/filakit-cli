# Changelog

All notable changes to this project will be documented in this file.

## [1.0.7] - 2026-08-26

### Bug Fixes

- Pin GitHub Actions to commit SHA (#3)
- **deps:** Update guzzlehttp/guzzle to patch security advisories

### CI/CD

- Pin actions to commit SHA, add dependabot cooldown/composer, trim dist archive
- **release:** Generate CHANGELOG.md and release notes with git-cliff

### Miscellaneous Tasks

- Bump guzzlehttp/guzzle and guzzlehttp/psr7 for security advisories

### Other

- Update starter kits list from plugins.json

## [1.0.6] - 2026-07-01

### Other

- Update starter kits list from plugins.json

## [1.0.5] - 2026-06-30

### Other

- Update starter kits list from plugins.json

## [1.0.4] - 2026-06-29

### Bug Fixes

- **ci:** Upload PHAR asset within release.yml; mark builds binary

### Other

- Update starter kits list from plugins.json

## [1.0.3] - 2026-06-29

### Bug Fixes

- **ci:** Build PHAR with the released tag, not stale version.txt (#2)
- **ci:** Build PHAR before tagging so release tags never move

## [1.0.2] - 2026-06-28

### Bug Fixes

- **ci:** Pick highest semver tag for auto-release version (#1)

## [0.0.17] - 2026-06-28

### Other

- Update starter kits list from plugins.json

## [0.0.16] - 2026-06-28

### CI/CD

- **build:** Serialize builds with a concurrency group to avoid ref-lock race
- **release:** Use version.txt as the single source of truth for the version

### Miscellaneous Tasks

- Bump version to 1.0.1

### Other

- Update starter kits list from plugins.json

### Refactor

- Use jeffersongoncalves/laravel-zero-self-update package

## [1.0.1] - 2026-05-13

### Miscellaneous Tasks

- Refresh portfolio banner

### Other

- Chain builds after Update Changelog + fix release-tagged rebuild

On the release path, three workflows fan out in parallel: publish-phar,
Update Changelog, and builds. Update Changelog force-pushes CHANGELOG
and version.txt, which raced with builds and caused non-fast-forward
rejections. Worse, the tag created by the release stayed on the commit
that existed before the PHAR was rebuilt, so `composer require` would
pull a PHAR with the previous version baked in.

This rewires build.yml to:

- Run via workflow_run after Update Changelog completes successfully,
  eliminating the race. Regular push on main still triggers.
- Pin ref and commit branch to main on workflow_run invocations
  (github.event.workflow_run.head_branch resolves to the tag name for
  release events and would land the commit on a detached HEAD / fail
  to push).
- Resolve the build version from workflow_run.head_branch when running
  under workflow_run. `git describe --tags --abbrev=0` is unreliable
  once the pre-release tag and current release tag share a commit.
- After the rebuild commit lands, move the release tag to that commit
  so Packagist (and direct git installs) serve the PHAR whose embedded
  version matches the tag.

Validated end-to-end in the git-worktree-cli sibling repo.

Co-Authored-By: Claude Opus 4.6 (1M context) <noreply@anthropic.com>
- Delete .github/workflows/dependabot-auto-merge.yml
- Update starter kits list from plugins.json

## [0.0.15] - 2026-03-31

### Other

- Fix Pint workflow: restrict to branch pushes to avoid detached HEAD on tags

Co-Authored-By: Claude Opus 4.6 (1M context) <noreply@anthropic.com>
- Remove PHPStan workflow (project has no PHPStan dependency)

Co-Authored-By: Claude Opus 4.6 (1M context) <noreply@anthropic.com>

## [0.0.14] - 2026-03-31

### Other

- Add MIT license

Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>
- Fix PHAR not being built for automated releases

GITHUB_TOKEN events don't trigger other workflows (GitHub limitation).
Add workflow_dispatch to publish-phar.yml and trigger it explicitly
from update-filakit.yml after creating a release.

Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>
- Fix test to match updated starter kit title 'Base Kit v5'

Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>
- Standardize GitHub workflows: update actions, add missing workflows (phpstan, pint, dependabot)
- Standardize .gitignore: replace /.claude with specific settings.local.json, remove /.ai
- Fix installer hanging on laravel/installer self-update prompt

Pass -n (no-interaction) and remove TTY to prevent the installer
from entering update mode and blocking the installation process.

Co-Authored-By: Claude Opus 4.6 (1M context) <noreply@anthropic.com>

## [0.0.13] - 2026-03-02

### Other

- Update starter kits list from plugins.json

## [0.0.12] - 2026-03-01

### Other

- Add CHANGELOG.md for automated release notes

Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>

## [0.0.11] - 2026-03-01

### Other

- Rename list:starterkits command to kits

Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>
- Fix test expecting outdated first starter kit title

Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>

## [0.0.10] - 2026-03-01

### Other

- Fix zlib data error after self-update on Windows

Exit the process immediately after replacing the PHAR to prevent
the framework shutdown from loading classes from the replaced file.

Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>

## [0.0.9] - 2026-03-01

### Other

- Add list:starterkits command to browse available starter kits

Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>
- Add laravel/installer passthrough options to new command

Forward 12 options (--git, --github, --branch, --organization, --database,
--pest, --npm, --pnpm, --bun, --yarn, --boost, --force) from the new command
directly to laravel new via InstallerService.

Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>

## [0.0.8] - 2026-03-01

### Other

- Fix PHAR validation failing when phar.readonly is enabled

Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>

## [0.0.7] - 2026-03-01

### Other

- Fix build workflow to fetch tags for version detection

Add fetch-depth: 0 to checkout so WyriHaximus/get-previous-tag
can find the latest release tag.

Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>
- Add self-update command for PHAR auto-update via GitHub Releases

Allows the PHAR-distributed CLI to check for and install updates directly
from GitHub release assets, with safe replacement (backup + fallback copy).

Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>

## [0.0.6] - 2026-03-01

### Other

- Add project banner and update README

Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>
- Fix update-filakit workflow for new plugins.json structure

Adapt jq and PHP parsing to handle nested startkit object
with featured/legacy sub-arrays instead of flat array.

Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>
- Update starter kits list from plugins.json

## [0.0.5] - 2026-02-24

### Other

- Update starter kits list from plugins.json

## [0.0.4] - 2026-02-24

### Other

- Update starter kits list from plugins.json

## [0.0.3] - 2026-02-24

### Other

- Update starter kits list from plugins.json

## [0.0.2] - 2026-02-24

### Other

- Add auto-update README starter kits section in update-filakit workflow

Uses STARTERKITS:START/END markers to replace the section automatically.
Groups kits by version and generates markdown tables.

Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>
- Update starter kits list from plugins.json
- Fix workflow to generate single quotes and run Pint, handle existing releases

- Generate config/starterkits.php with single quotes (Pint compliance)
- Run Pint in workflow after generating config
- Install composer deps in workflow for Pint availability
- Increment version if tag already exists to avoid release conflicts

Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>
- Add Filament version selection and numbered starter kit list

- Ask which Filament version (v3, v4, v5) before showing kits
- Filter starter kits by selected version
- Show numbered list (1, 2, 3...) for easier selection
- Add --filament option to skip version prompt
- StarterKit DTO now detects version from package/title
- 18 tests passing (57 assertions)

Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>
- Update starter kits list from plugins.json

## [0.0.1] - 2026-02-24

### Other

- Initial commit: Filakit CLI with Laravel Zero

CLI tool for scaffolding Laravel projects with Filakit starter kits.
Fetches available kits from remote JSON and runs laravel new --using.

- NewCommand with interactive starter kit selection (Laravel Prompts)
- StarterKitService for remote JSON fetching via Guzzle
- InstallerService wrapping laravel new process execution
- StarterKit DTO with fromArray/toArray
- 12 Pest tests (unit + feature) all passing
- CI workflows: tests, build, publish-phar, update-changelog
- Webhook workflow for auto-releasing on starter kit updates
- Box PHAR configuration for compilation

Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>
- Embed starter kits list and add webhook workflow for auto-update

Replace runtime HTTP fetch with compiled config/starterkits.php.
GitHub Action fetches plugins.json, extracts startkits, updates config,
commits and creates release to trigger PHAR build.

- Remove Guzzle dependency (no longer needed)
- StarterKitService reads from config() instead of HTTP
- update-filakit workflow compiles list and auto-releases on changes
- Updated tests to use config-based approach (13 passing)

Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>
- Update README with project documentation, usage examples and starter kits list

Co-Authored-By: Claude Opus 4.6 <noreply@anthropic.com>
- Update starter kits list from plugins.json


