<?php

namespace App\Commands;

use App\Services\InstallerService;
use App\Services\StarterKitService;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class NewCommand extends Command
{
    protected $signature = 'new
        {name? : The name of the application}
        {--kit= : The starter kit package to use}
        {--filament= : The Filament version (v3, v4, v5)}
        {--git : Initialize a Git repository}
        {--github=none : Create a GitHub repository (optionally: "private" or "public")}
        {--branch= : The default branch for the repository}
        {--organization= : The GitHub organization to create the repository for}
        {--database= : The database driver (mysql, sqlite, pgsql, mariadb)}
        {--pest : Install Pest testing framework}
        {--npm : Use npm as the package manager}
        {--pnpm : Use pnpm as the package manager}
        {--bun : Use Bun as the package manager}
        {--yarn : Use Yarn as the package manager}
        {--boost : Install Laravel Boost}
        {--f|force : Force install even if the directory already exists}';

    protected $description = 'Create a new Laravel application using a Filakit starter kit';

    public function handle(StarterKitService $starterKitService, InstallerService $installerService): int
    {
        $name = $this->argument('name') ?? text(
            label: 'What is the name of your application?',
            required: true,
        );

        $kitPackage = $this->option('kit');

        if (! $kitPackage) {
            $versions = $starterKitService->getAvailableVersions();

            if (empty($versions)) {
                $this->components->error('No starter kits available.');

                return self::FAILURE;
            }

            $versionOptions = [];
            foreach ($versions as $v) {
                $versionOptions[$v] = 'Filament '.$v;
            }

            $version = $this->option('filament') ?? select(
                label: 'Which Filament version do you want to use?',
                options: $versionOptions,
            );

            $options = $starterKitService->getStarterKitOptions($version);

            if (empty($options)) {
                $this->components->error("No starter kits available for {$version}.");

                return self::FAILURE;
            }

            $selected = select(
                label: 'Which starter kit would you like to use?',
                options: $options,
            );

            $kit = $starterKitService->findByIndex($version, (int) $selected);
            $kitPackage = $kit->package;
        }

        $extraArgs = $this->buildExtraArgs();

        $this->newLine();
        $this->components->info("Creating <comment>{$name}</comment> with <comment>{$kitPackage}</comment>...");
        $this->newLine();

        $success = $installerService->run($name, $kitPackage, $extraArgs, function (string $type, string $buffer): void {
            $this->output->write($buffer);
        });

        if (! $success) {
            $this->newLine();
            $this->components->error('Failed to create the application.');

            return self::FAILURE;
        }

        $this->newLine();
        $this->components->info("Application <comment>{$name}</comment> created successfully!");

        return self::SUCCESS;
    }

    /**
     * @return list<string>
     */
    private function buildExtraArgs(): array
    {
        $args = [];

        $booleanFlags = ['git', 'pest', 'npm', 'pnpm', 'bun', 'yarn', 'boost', 'force'];

        foreach ($booleanFlags as $flag) {
            if ($this->option($flag)) {
                $args[] = "--{$flag}";
            }
        }

        $github = $this->option('github');
        if ($github !== null && $github !== 'none') {
            $args[] = $github === '' ? '--github' : "--github={$github}";
        }

        $valueOptions = ['branch', 'organization', 'database'];

        foreach ($valueOptions as $opt) {
            $value = $this->option($opt);
            if ($value !== null) {
                $args[] = "--{$opt}={$value}";
            }
        }

        return $args;
    }
}
