<?php

declare(strict_types=1);

namespace FondBot\Channels;

class Channel
{
    private $name;
    private $driver;
    private $secret;

    public function __construct(string $name, Driver $driver, string $secret = null)
    {
        $this->name = $name;
        $this->driver = $driver;
        $this->secret = $secret;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDriver(): Driver
    {
        return $this->driver;
    }

    public function getSecret(): ?string
    {
        return $this->secret;
    }

    public function getWebhookUrl(): string
    {
        return route('fondbot.webhook', [$this->name, $this->secret]);
    }
}
