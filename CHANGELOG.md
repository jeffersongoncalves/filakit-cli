# Changelog

All notable changes to this project will be documented in this file.

## [1.1.1] - 2026-10-22

### Bug Fixes

- **ci:** Keep release notes to commit subjects and skip grouped bumps (#17)

## [1.1.0] - 2026-10-22

### Features

- Add add-repo command to register private starter kits via SSH (#16)

## [1.0.9] - 2026-10-08

### CI/CD

- Pass release version input through env
- Standardize dependabot config
- Standardize tests workflow
- Remove run-tests.yml (replaced by tests.yml)
- Standardize tests workflow
- Auto-merge dependabot github-actions minor/patch

### Documentation

- Point tests badge to tests.yml

### Other

- Update starter kits list from plugins.json

## [1.0.8] - 2026-09-08

### Bug Fixes

- **ci:** Flatten nested legacy startkit groups in plugins.json parsing
- **ci:** Publish release as draft until PHAR asset is attached

### Documentation

- Add Buy Me a Coffee sponsor link (#6)

### Miscellaneous Tasks

- Add GitHub Sponsors to FUNDING.yml (#7)

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
- Delete .github/workflows/dependabot-auto-merge.yml
- Update starter kits list from plugins.json

## [0.0.15] - 2026-03-31

### Other

- Fix Pint workflow: restrict to branch pushes to avoid detached HEAD on tags
- Remove PHPStan workflow (project has no PHPStan dependency)

## [0.0.14] - 2026-03-31

### Other

- Add MIT license
- Fix PHAR not being built for automated releases
- Fix test to match updated starter kit title 'Base Kit v5'
- Standardize GitHub workflows: update actions, add missing workflows (phpstan, pint, dependabot)
- Standardize .gitignore: replace /.claude with specific settings.local.json, remove /.ai
- Fix installer hanging on laravel/installer self-update prompt

## [0.0.13] - 2026-03-02

### Other

- Update starter kits list from plugins.json

## [0.0.12] - 2026-03-01

### Other

- Add CHANGELOG.md for automated release notes

## [0.0.11] - 2026-03-01

### Other

- Rename list:starterkits command to kits
- Fix test expecting outdated first starter kit title

## [0.0.10] - 2026-03-01

### Other

- Fix zlib data error after self-update on Windows

## [0.0.9] - 2026-03-01

### Other

- Add list:starterkits command to browse available starter kits
- Add laravel/installer passthrough options to new command

## [0.0.8] - 2026-03-01

### Other

- Fix PHAR validation failing when phar.readonly is enabled

## [0.0.7] - 2026-03-01

### Other

- Fix build workflow to fetch tags for version detection
- Add self-update command for PHAR auto-update via GitHub Releases

## [0.0.6] - 2026-03-01

### Other

- Add project banner and update README
- Fix update-filakit workflow for new plugins.json structure
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
- Update starter kits list from plugins.json
- Fix workflow to generate single quotes and run Pint, handle existing releases
- Add Filament version selection and numbered starter kit list
- Update starter kits list from plugins.json

## [0.0.1] - 2026-02-24

### Other

- Initial commit: Filakit CLI with Laravel Zero
- Embed starter kits list and add webhook workflow for auto-update
- Update README with project documentation, usage examples and starter kits list
- Update starter kits list from plugins.json


