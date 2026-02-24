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
        {--kit= : The starter kit package to use}';

    protected $description = 'Create a new Laravel application using a Filakit starter kit';

    public function handle(StarterKitService $starterKitService, InstallerService $installerService): int
    {
        $name = $this->argument('name') ?? text(
            label: 'What is the name of your application?',
            required: true,
        );

        $kitPackage = $this->option('kit');

        if (! $kitPackage) {
            $options = $starterKitService->getStarterKitOptions();

            if (empty($options)) {
                $this->components->error('No starter kits available.');

                return self::FAILURE;
            }

            $kitPackage = select(
                label: 'Which starter kit would you like to use?',
                options: $options,
            );
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
