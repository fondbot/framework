<?php
declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Channels\Abstracts\Driver;
use FondBot\Channels\Objects\Participant;
use Illuminate\Contracts\Support\Arrayable;

class Context implements Arrayable
{

    /** @var Driver */
    private $driver;

    /** @var Participant */
    private $participant;

    /** @var Story|null */
    private $story;

    /** @var Interaction|null */
    private $interaction;

    /** @var array */
    private $values;

    public function __construct(
        Driver $driver,
        Participant $participant,
        ?Story $story,
        ?Interaction $interaction,
        array $values = []
    ) {
        $this->driver = $driver;
        $this->participant = $participant;
        $this->story = $story;
        $this->interaction = $interaction;
        $this->values = $values;
    }

    public function getDriver(): Driver
    {
        return $this->driver;
    }

    public function setStory(Story $story): void
    {
        $this->story = $story;
    }

    public function getStory(): ?Story
    {
        return $this->story;
    }

    public function setInteraction(Interaction $interaction): void
    {
        $this->interaction = $interaction;
    }

    public function getInteraction(): ?Interaction
    {
        return $this->interaction;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'channel' => $this->driver,
            'participant' => $this->participant,
            'story' => $this->story,
            'interaction' => $this->interaction,
            'values' => $this->values,
        ];
    }

}