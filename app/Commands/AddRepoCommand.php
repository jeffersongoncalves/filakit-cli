<?php

namespace App\Commands;

use App\Services\RepositoryService;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\text;

class AddRepoCommand extends Command
{
    protected $signature = 'add-repo
        {url? : The SSH URL of the private starter kit (e.g. git@github.com:vendor/my-skeleton.git)}';

    protected $description = 'Register a private starter kit repository (via SSH) in the global Composer config';

    public function handle(RepositoryService $repositoryService): int
    {
        $url = $this->argument('url') ?? text(
            label: 'What is the SSH URL of the private repository?',
            placeholder: 'git@github.com:vendor/my-skeleton.git',
            required: true,
        );

        // ssh://user@host[:port]/path or scp-style user@host:path
        if (! preg_match('#^(?:ssh://([^@/]+)@([^:/]+)(?::\d+)?/|([^@/:]+)@([^:/]+):)(.+/[^/]+?)(?:\.git)?/?$#', $url, $matches)) {
            $this->components->error('Invalid SSH URL. Expected format: git@github.com:vendor/repo.git');

            return self::FAILURE;
        }

        $user = $matches[1] ?: $matches[3];
        $host = $matches[2] ?: $matches[4];
        $package = implode('/', array_slice(explode('/', $matches[5]), -2));

        $this->components->task("Checking SSH access to <comment>{$url}</comment>", function () use ($repositoryService, $url, &$canAccess): bool {
            return $canAccess = $repositoryService->canAccess($url);
        });

        if (! $canAccess) {
            $this->components->error("Cannot access {$url} via SSH.");
            $this->line('  Set up an SSH key and try again:');
            $this->line('    1. <comment>ssh-keygen -t ed25519 -C "you@example.com"</comment>');
            $this->line("    2. Add <comment>~/.ssh/id_ed25519.pub</comment> to your {$host} account (GitHub: https://github.com/settings/keys)");
            $this->line("    3. Test it: <comment>ssh -T {$user}@{$host}</comment>");

            return self::FAILURE;
        }

        if (! $repositoryService->register(str_replace('/', '--', $package), $url)) {
            $this->components->error('Failed to register the repository in the global Composer config.');

            return self::FAILURE;
        }

        $this->components->info("Repository <comment>{$url}</comment> registered in the global Composer config.");
        $this->line('  Create your application with:');
        $this->line("    <comment>filakit new my-app --kit={$package}</comment>");

        return self::SUCCESS;
    }
}
