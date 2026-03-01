<?php

namespace App\Commands;

use App\Services\StarterKitService;
use LaravelZero\Framework\Commands\Command;

class ListStarterKitsCommand extends Command
{
    protected $signature = 'list:starterkits
        {--filament= : Filter by Filament version (v3, v4, v5)}';

    protected $description = 'List all available Filakit starter kits';

    public function handle(StarterKitService $starterKitService): int
    {
        $filterVersion = $this->option('filament');

        if ($filterVersion) {
            return $this->listVersion($starterKitService, $filterVersion);
        }

        return $this->listAll($starterKitService);
    }

    private function listAll(StarterKitService $starterKitService): int
    {
        $versions = $starterKitService->getAvailableVersions();

        if (empty($versions)) {
            $this->components->error('No starter kits available.');

            return self::FAILURE;
        }

        foreach ($versions as $version) {
            $this->renderVersion($starterKitService, $version);
        }

        return self::SUCCESS;
    }

    private function listVersion(StarterKitService $starterKitService, string $version): int
    {
        $kits = $starterKitService->getStarterKitsByVersion($version);

        if (empty($kits)) {
            $this->components->error("No starter kits available for {$version}.");

            return self::FAILURE;
        }

        $this->renderVersion($starterKitService, $version);

        return self::SUCCESS;
    }

    private function renderVersion(StarterKitService $starterKitService, string $version): void
    {
        $kits = $starterKitService->getStarterKitsByVersion($version);

        $this->newLine();
        $this->components->info('Filament '.$version);

        foreach ($kits as $kit) {
            $this->components->twoColumnDetail($kit->title, $kit->package);
        }

        $this->newLine();
    }
}
