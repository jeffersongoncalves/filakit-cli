<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class InstallerService
{
    public function run(string $name, string $kitPackage, ?callable $output = null): bool
    {
        $process = new Process(['laravel', 'new', $name, "--using={$kitPackage}"]);
        $process->setTimeout(null);
        $process->setTty(Process::isTtySupported());

        $process->run($output);

        return $process->isSuccessful();
    }
}
