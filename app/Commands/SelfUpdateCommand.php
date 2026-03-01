<?php

namespace App\Commands;

use App\Services\SelfUpdateService;
use LaravelZero\Framework\Commands\Command;

class SelfUpdateCommand extends Command
{
    protected $signature = 'self-update
        {--check : Only check for updates without installing}';

    protected $description = 'Update the Filakit CLI to the latest version';

    public function handle(SelfUpdateService $selfUpdateService): int
    {
        if (! $selfUpdateService->isRunningAsPhar()) {
            $this->components->error('Self-update is only available when running as a PHAR. Use Git or Composer to update instead.');

            return self::FAILURE;
        }

        $currentVersion = $selfUpdateService->getCurrentVersion();
        $this->components->info("Current version: <comment>{$currentVersion}</comment>");

        try {
            $release = $selfUpdateService->getLatestRelease();
        } catch (\RuntimeException $e) {
            $this->components->error($e->getMessage());

            return self::FAILURE;
        }

        $latestTag = $release['tag'];

        if (! $selfUpdateService->isUpdateAvailable($currentVersion, $latestTag)) {
            $this->components->info('You are already using the latest version.');

            return self::SUCCESS;
        }

        $this->components->info("A new version is available: <comment>{$latestTag}</comment>");

        if ($this->option('check')) {
            return self::SUCCESS;
        }

        try {
            $tempFile = null;

            $this->components->task('Downloading update', function () use ($selfUpdateService, $release, &$tempFile) {
                $tempFile = $selfUpdateService->download($release['url']);
            });

            $this->components->task('Replacing PHAR', function () use ($selfUpdateService, $tempFile) {
                $selfUpdateService->replacePhar($tempFile);
            });
        } catch (\RuntimeException $e) {
            $this->components->error($e->getMessage());

            return self::FAILURE;
        }

        $this->newLine();
        $this->components->info("Successfully updated to <comment>{$latestTag}</comment>.");

        return self::SUCCESS;
    }
}
