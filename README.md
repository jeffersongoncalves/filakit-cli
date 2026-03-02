<div class="filament-hidden">

![Filakit CLI](https://raw.githubusercontent.com/jeffersongoncalves/filakit-cli/main/art/jeffersongoncalves-filakit-cli.png)

</div>

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

### Additional Options

All options from the Laravel installer are supported and forwarded directly:

| Option                  | Description                                             |
|-------------------------|---------------------------------------------------------|
| `--git`                 | Initialize a Git repository                             |
| `--github[=VISIBILITY]` | Create a GitHub repository (`private` or `public`)      |
| `--branch=NAME`         | Default branch for the repository                       |
| `--organization=ORG`    | GitHub organization for the repository                  |
| `--database=DRIVER`     | Database driver (`mysql`, `sqlite`, `pgsql`, `mariadb`) |
| `--pest`                | Install Pest testing framework                          |
| `--npm`                 | Use npm as the package manager                          |
| `--pnpm`                | Use pnpm as the package manager                         |
| `--bun`                 | Use Bun as the package manager                          |
| `--yarn`                | Use Yarn as the package manager                         |
| `--boost`               | Install Laravel Boost                                   |
| `-f`, `--force`         | Force install even if the directory already exists      |

**Examples:**

```bash
# Create with Git + Pest + pnpm
filakit new my-app --kit=jeffersongoncalves/filakitv5 --git --pest --pnpm

# Create with a GitHub repo under an organization
filakit new my-app --kit=jeffersongoncalves/filakitv5 --github=private --organization=my-org

# Force overwrite with a specific database
filakit new my-app --kit=jeffersongoncalves/filakitv5 --database=pgsql --force
```

<!-- STARTERKITS:START -->
## Available Starter Kits

### Filament v5

| Kit | Package |
|-----|--------|
| Base Kit v5 | `filakitphp/basev5` |
| Fila Kit v5 | `jeffersongoncalves/filakitv5` |
| Native Kit v5 | `jeffersongoncalves/nativekitv5` |
| Mobile Kit v5 | `jeffersongoncalves/mobilekitv5` |
| Team Kit v5 | `jeffersongoncalves/teamkitv5` |
| Service Desk Kit v5 | `jeffersongoncalves/servicedeskkitv5` |
| Help Desk Kit v5 | `jeffersongoncalves/helpdeskkitv5` |
| Evolution Kit v5 | `jeffersongoncalves/evolutionkitv5` |
| MFA Kit v5 | `jeffersongoncalves/mfakitv5` |

### Filament v4

| Kit | Package |
|-----|--------|
| Base Kit v4 | `filakitphp/basev4` |
| Fila Kit v4 | `jeffersongoncalves/filakitv4` |
| Native Kit v4 | `jeffersongoncalves/nativekitv4` |
| Mobile Kit v4 | `jeffersongoncalves/mobilekitv4` |
| Team Kit v4 | `jeffersongoncalves/teamkitv4` |
| Service Desk Kit v4 | `jeffersongoncalves/servicedeskkitv4` |
| Help Desk Kit v4 | `jeffersongoncalves/helpdeskkitv4` |
| Evolution Kit v4 | `jeffersongoncalves/evolutionkitv4` |
| MFA Kit v4 | `jeffersongoncalves/mfakitv4` |

### Filament v3

| Kit | Package |
|-----|--------|
| Base Kit v3 | `filakitphp/basev3` |
| Fila Kit v3 | `jeffersongoncalves/filakit` |
| Native Kit v3 | `jeffersongoncalves/nativekit` |
| Mobile Kit v3 | `jeffersongoncalves/mobilekit` |
| Team Kit v3 | `jeffersongoncalves/teamkit` |
| Service Desk Kit v3 | `jeffersongoncalves/servicedeskkitv3` |
| Help Desk Kit v3 | `jeffersongoncalves/helpdeskkitv3` |
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
