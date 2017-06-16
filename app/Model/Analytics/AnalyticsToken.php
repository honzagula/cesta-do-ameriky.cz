<?php

namespace App\Analytics;

use Google_Client;
use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;

class AnalyticsToken
{
    private $keyFileLocation = __DIR__ . '/../../config/ga-key.json'; // todo: move to config

    private $tempDir;

    public function __construct(string $keyFileLocation, string $tempDir)
    {
        $this->keyFileLocation = $keyFileLocation;
        $this->tempDir = $tempDir;
    }

    public function get(): ?string
    {
        $cache = new Cache(new FileStorage($this->tempDir));
        $result = $cache->load('gaAccessToken');
        if ($result === null) {
            $result = $this->createToken();
            if ($result === null) {
                return null;
            }
            $cache->save(
                'gaAccessToken',
                $result,
                [Cache::EXPIRE => '3600 seconds']
            );
        }

        return $result;
    }

    private function createToken(): ?string
    {
        if (!file_exists($this->keyFileLocation)) {
            return null;
        }
        $client = new Google_Client();
        $client->setApplicationName("Hello Analytics Reporting");
        $client->setAuthConfig($this->keyFileLocation);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);

        $client->fetchAccessTokenWithAssertion();
        return $client->getAccessToken()['access_token'];
    }
}