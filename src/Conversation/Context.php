<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Contracts\LoggableArray;
use FondBot\Contracts\Channels\Driver;

class Context implements LoggableArray
{
    /** @var Driver */
    private $driver;

    /** @var Story|null */
    private $story;

    /** @var Interaction|null */
    private $interaction;

    /** @var array */
    private $values;

    public function __construct(
        Driver $driver,
        Story $story = null,
        Interaction $interaction = null,
        array $values = []
    ) {
        $this->driver = $driver;
        $this->story = $story;
        $this->interaction = $interaction;
        $this->values = $values;
    }

    public function getDriver(): Driver
    {
        return $this->driver;
    }

    public function getStory(): ?Story
    {
        return $this->story;
    }

    public function setStory(Story $story): void
    {
        $this->story = $story;
    }

    public function getInteraction(): ?Interaction
    {
        return $this->interaction;
    }

    public function setInteraction(?Interaction $interaction): void
    {
        $this->interaction = $interaction;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function setValues(array $values): void
    {
        $this->values = $values;
    }

    /**
     * Return information for log.
     *
     * @return array
     */
    public function toLoggableArray(): array
    {
        return [
            'driver' => get_class($this->getDriver()),
            'story' => get_class($this->getStory()),
            'interaction' => get_class($this->getInteraction()),
            'values' => $this->getValues(),
        ];
    }
}
