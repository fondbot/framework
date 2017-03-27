<?php

declare(strict_types=1);

namespace FondBot\Channels;

class Channel
{
    private $name;
    private $parameters;

    public function __construct(string $name, array $parameters)
    {
        $this->name = $name;
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
     * Channel parameters.
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
