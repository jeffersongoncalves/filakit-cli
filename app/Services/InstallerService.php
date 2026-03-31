<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class InstallerService
{
    public function run(string $name, string $kitPackage, array $extraArgs = [], ?callable $output = null): bool
    {
        $process = new Process(['laravel', 'new', $name, "--using={$kitPackage}", '-n', ...$extraArgs]);
        $process->setTimeout(null);

        $process->run($output);

        return $process->isSuccessful();
    }
}
