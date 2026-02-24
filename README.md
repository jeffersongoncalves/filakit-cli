# Filakit CLI

<p align="center">
  <a href="https://github.com/jeffersongoncalves/filakit-cli/actions/workflows/run-tests.yml"><img src="https://github.com/jeffersongoncalves/filakit-cli/actions/workflows/run-tests.yml/badge.svg" alt="Tests" /></a>
  <a href="https://github.com/jeffersongoncalves/filakit-cli/actions/workflows/build.yml"><img src="https://github.com/jeffersongoncalves/filakit-cli/actions/workflows/build.yml/badge.svg" alt="Build" /></a>
  <a href="https://github.com/jeffersongoncalves/filakit-cli/releases/latest"><img src="https://img.shields.io/github/v/release/jeffersongoncalves/filakit-cli" alt="Latest Release" /></a>
  <img src="https://img.shields.io/badge/php-%3E%3D8.2-8892BF" alt="PHP 8.2+" />
  <a href="LICENSE"><img src="https://img.shields.io/github/license/jeffersongoncalves/filakit-cli" alt="License" /></a>
</p>

CLI tool for scaffolding Laravel projects with **Filakit starter kits**. Select from available starter kits and create a new Laravel application with a single command.

## Requirements

- PHP >= 8.2
- [Laravel Installer](https://laravel.com/docs/installation) (`composer global require laravel/installer`)

## Installation

### Download PHAR (recommended)

Download the latest `filakit.phar` from the [Releases](https://github.com/jeffersongoncalves/filakit-cli/releases) page:

```bash
# Download and make executable
curl -sL https://github.com/jeffersongoncalves/filakit-cli/releases/latest/download/filakit.phar -o filakit
chmod +x filakit
sudo mv filakit /usr/local/bin/filakit
```

### Via Composer (global)

```bash
composer global require jeffersongoncalves/filakit-cli
```

## Usage

### Interactive mode

```bash
filakit new
```

The CLI will prompt you for:
1. **Application name** - the name of your new project
2. **Starter kit** - select from the available kits

### With arguments

```bash
filakit new my-app
```

### Skip selection with `--kit`

```bash
filakit new my-app --kit=jeffersongoncalves/filakitv5
```

<!-- STARTERKITS:START -->
## Available Starter Kits

### Filament v5

| Kit | Package |
|-----|--------|
| Filakit v5 | `jeffersongoncalves/filakitv5` |
| Nativekit v5 | `jeffersongoncalves/nativekitv5` |
| Mobilekit v5 | `jeffersongoncalves/mobilekitv5` |
| Teamkit v5 | `jeffersongoncalves/teamkitv5` |
| Service Desk Kit v5 | `jeffersongoncalves/servicedeskkitv5` |

### Filament v4

| Kit | Package |
|-----|--------|
| Filakit v4 | `jeffersongoncalves/filakitv4` |
| Nativekit v4 | `jeffersongoncalves/nativekitv4` |
| Mobilekit v4 | `jeffersongoncalves/mobilekitv4` |
| Teamkit v4 | `jeffersongoncalves/teamkitv4` |
| Evolutionkit v4 | `jeffersongoncalves/evolutionkitv4` |
| MFAkit v4 | `jeffersongoncalves/mfakitv4` |

### Filament v3

| Kit | Package |
|-----|--------|
| Filakit v3 | `jeffersongoncalves/filakit` |
| Nativekit v3 | `jeffersongoncalves/nativekit` |
| Mobilekit v3 | `jeffersongoncalves/mobilekit` |
| Teamkit v3 | `jeffersongoncalves/teamkit` |
<!-- STARTERKITS:END -->

## How It Works

Under the hood, Filakit CLI runs `laravel new` with the `--using` flag:

```bash
laravel new my-app --using=jeffersongoncalves/filakitv5
```

The list of available starter kits is embedded in the CLI and automatically updated via GitHub Actions when the [source list](https://raw.githubusercontent.com/jeffersongoncalves/jeffersongoncalves/refs/heads/master/plugins.json) changes.

## Development

```bash
# Clone
git clone git@github.com:jeffersongoncalves/filakit-cli.git
cd filakit-cli

# Install dependencies
composer install

# Run tests
php vendor/bin/pest

# Run code formatting
php vendor/bin/pint

# Build PHAR
php filakit app:build filakit
```

## License

Filakit CLI is open-source software licensed under the [MIT license](LICENSE).
