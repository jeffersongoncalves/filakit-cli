<?php

namespace App\Services;

use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;

class RepositoryService
{
    public function canAccess(string $url): bool
    {
        return $this->run(['git', 'ls-remote', '--heads', $url]);
    }

    public function register(string $name, string $url): bool
    {
        // The git driver has no dist archives, so Composer always clones over SSH (no HTTPS token needed).
        $repository = json_encode(['type' => 'git', 'url' => $url], JSON_UNESCAPED_SLASHES);

        return $this->run(['composer', 'config', '--global', "repositories.{$name}", $repository]);
    }

    /**
     * @param  list<string>  $command
     */
    private function run(array $command): bool
    {
        $process = new Process($command);
        $process->setTimeout(120);

        try {
            $process->run();
        } catch (ProcessTimedOutException) {
            return false;
        }

        return $process->isSuccessful();
    }
}
