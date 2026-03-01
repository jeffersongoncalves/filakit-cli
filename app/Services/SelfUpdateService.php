<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;

class SelfUpdateService
{
    private const GITHUB_REPO = 'jeffersongoncalves/filakit-cli';

    private const ASSET_NAME = 'filakit.phar';

    public function __construct(
        private readonly Client $client = new Client,
    ) {}

    public function getCurrentVersion(): string
    {
        return config('app.version', 'unreleased');
    }

    public function isRunningAsPhar(): bool
    {
        $pharPath = \Phar::running(false);

        return $pharPath !== '';
    }

    /**
     * @return array{tag: string, url: string}
     *
     * @throws RuntimeException
     */
    public function getLatestRelease(): array
    {
        try {
            $response = $this->client->get(
                'https://api.github.com/repos/'.self::GITHUB_REPO.'/releases/latest',
                ['headers' => ['Accept' => 'application/vnd.github+json']]
            );
        } catch (GuzzleException $e) {
            throw new RuntimeException('Failed to fetch latest release from GitHub: '.$e->getMessage());
        }

        $data = json_decode($response->getBody()->getContents(), true);
        $tag = $data['tag_name'] ?? null;

        if (! $tag) {
            throw new RuntimeException('Invalid release data from GitHub.');
        }

        $downloadUrl = null;
        foreach ($data['assets'] ?? [] as $asset) {
            if ($asset['name'] === self::ASSET_NAME) {
                $downloadUrl = $asset['browser_download_url'];
                break;
            }
        }

        if (! $downloadUrl) {
            throw new RuntimeException('PHAR asset not found in the latest release.');
        }

        return ['tag' => $tag, 'url' => $downloadUrl];
    }

    public function isUpdateAvailable(string $currentVersion, string $latestTag): bool
    {
        $current = ltrim($currentVersion, 'v');
        $latest = ltrim($latestTag, 'v');

        if ($current === 'unreleased') {
            return true;
        }

        return version_compare($current, $latest, '<');
    }

    /**
     * @throws RuntimeException
     */
    public function download(string $url): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'filakit_');

        if ($tempFile === false) {
            throw new RuntimeException('Failed to create temporary file.');
        }

        try {
            $this->client->get($url, ['sink' => $tempFile]);
        } catch (GuzzleException $e) {
            @unlink($tempFile);

            throw new RuntimeException('Failed to download the PHAR file: '.$e->getMessage());
        }

        if (! $this->isValidPhar($tempFile)) {
            @unlink($tempFile);

            throw new RuntimeException('Downloaded file is not a valid PHAR.');
        }

        return $tempFile;
    }

    /**
     * @throws RuntimeException
     */
    public function replacePhar(string $tempFile): void
    {
        $pharPath = \Phar::running(false);

        if ($pharPath === '') {
            @unlink($tempFile);

            throw new RuntimeException('Cannot determine current PHAR path.');
        }

        $backupPath = $pharPath.'.backup';

        // Create backup
        if (! @copy($pharPath, $backupPath)) {
            @unlink($tempFile);

            throw new RuntimeException('Failed to create backup of current PHAR.');
        }

        // Try rename first (atomic on same filesystem), fall back to copy (Windows cross-drive)
        $replaced = @rename($tempFile, $pharPath) || @copy($tempFile, $pharPath);

        if (! $replaced) {
            @rename($backupPath, $pharPath);
            @unlink($tempFile);

            throw new RuntimeException('Failed to replace PHAR file.');
        }

        @chmod($pharPath, 0755);
        @unlink($backupPath);
        @unlink($tempFile);
    }

    private function isValidPhar(string $path): bool
    {
        try {
            new \Phar($path);

            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
