<?php

declare(strict_types=1);

namespace FondBot\Channels;

class Channel
{
    private $name;
    private $driver;
    private $parameters;

    public function __construct(string $name, string $driver, array $parameters)
    {
        $this->name = $name;
        $this->driver = $driver;
        $this->parameters = $parameters;
    }

    /**
     * Name of the channel.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get driver name.
     *
     * @return string
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * Channel parameters.
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
