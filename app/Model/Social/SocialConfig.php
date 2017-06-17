<?php

namespace App\Model\Social;

class SocialConfig
{
    private $facebookAppId;
    private $googleAnalyticsId;
    private $googleAnalyticsViewId;

    public function __construct(?string $facebookAppId, ?string $googleAnalyticsId, ?int $googleAnalyticsViewId)
    {
        $this->facebookAppId = $facebookAppId;
        $this->googleAnalyticsId = $googleAnalyticsId;
        $this->googleAnalyticsViewId = $googleAnalyticsViewId;
    }

    public function getFacebookAppId(): string
    {
        return $this->facebookAppId;
    }

    public function getGoogleAnalyticsId(): string
    {
        return $this->googleAnalyticsId;
    }

    public function getGoogleAnalyticsViewId(): int
    {
        return $this->googleAnalyticsViewId;
    }
}