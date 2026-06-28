# Changelog

All notable changes to `filakit-cli` will be documented in this file.

## v1.0.2 - 2026-06-28

Fix auto-release versioning. Continues from 1.0.1.

## 1.0.1 - 2026-06-06

Adopt version.txt release flow (version.txt as version source, no tag-move; concurrency on builds).

## 1.0.0 - 2026-05-07

**Full Changelog**: https://github.com/jeffersongoncalves/filakit-cli/compare/v0.0.15...1.0.0

## v1.0.0 - 2026-05-07

**Full Changelog**: https://github.com/jeffersongoncalves/filakit-cli/compare/v0.0.15...v1.0.0

## v0.0.15 - 2026-03-31

- Fix Pint workflow failing on tag pushes (detached HEAD)
- Remove PHPStan workflow (not a project dependency)

## v0.0.14 - 2026-03-31

Fix installer hanging on laravel/installer self-update prompt. Pass -n (no-interaction) and remove TTY to prevent blocking.

## v0.0.12 - 2026-03-01

### Fixed

- Add missing CHANGELOG.md to enable automated release notes
