<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class RepositoryService
{
    public function canAccess(string $url): bool
    {
        $process = new Process(['git', 'ls-remote', '--heads', $url]);
        $process->setTimeout(120);

        $process->run();

        return $process->isSuccessful();
    }

    public function register(string $name, string $url): bool
    {
        $repository = json_encode(['type' => 'vcs', 'url' => $url, 'no-api' => true], JSON_UNESCAPED_SLASHES);

        $process = new Process(['composer', 'config', '--global', "repositories.{$name}", $repository]);
        $process->run();

        return $process->isSuccessful();
    }
}
