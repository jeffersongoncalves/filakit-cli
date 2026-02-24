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
        {--filament= : The Filament version (v3, v4, v5)}';

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

        $this->newLine();
        $this->components->info("Creating <comment>{$name}</comment> with <comment>{$kitPackage}</comment>...");
        $this->newLine();

        $success = $installerService->run($name, $kitPackage, function (string $type, string $buffer): void {
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
}
