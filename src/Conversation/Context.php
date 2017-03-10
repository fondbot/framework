<?php declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Channels\Abstracts\Driver;
use FondBot\Channels\Objects\Message;
use FondBot\Channels\Objects\Participant;
use FondBot\Conversation\Abstracts\Interaction;
use FondBot\Conversation\Abstracts\Story;
use Illuminate\Contracts\Support\Arrayable;

class Context implements Arrayable
{

    /** @var Driver */
    private $channel;

    /** @var Participant */
    private $participant;

    /** @var Message */
    private $message;

    /** @var Story|null */
    private $story;

    /** @var Interaction|null */
    private $interaction;

    /** @var array */
    private $values;

    public function __construct(
        Driver $channel,
        Participant $participant,
        Message $message,
        ?Story $story,
        ?Interaction $interaction,
        array $values = []
    ) {
        $this->channel = $channel;
        $this->participant = $participant;
        $this->message = $message;
        $this->story = $story;
        $this->interaction = $interaction;
        $this->values = $values;
    }

    public static function instance(Driver $channel): Context
    {
        $key = self::key($channel->name(), $channel->participant());

        $value = cache($key);

        logger('Context.instance', ['value' => $value]);

        $story = $value['story'] !== null ? app($value['story']) : null;
        $interaction = $value['interaction'] !== null ? app($value['interaction']) : null;

        return new static(
            $channel,
            $channel->participant(),
            $channel->message(),
            $story,
            $interaction,
            $value['values'] ?? []
        );
    }

    public function setChannel(Driver $channel): void
    {
        $this->channel = $channel;
    }

    public function getChannel(): Driver
    {
        return $this->channel;
    }

    public function getMessage(): Message
    {
        return $this->message;
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
     * @param string $key
     * @return mixed|null
     */
    public function getValue(string $key)
    {
        return $this->values[$key] ?? null;
    }

    public function setValue(string $key, $value): void
    {
        $this->values[$key] = $value;
    }

    public function save(): void
    {
        $key = self::key($this->channel->name(), $this->channel->participant());

        $value = [
            'story' => $this->getStory() !== null ? get_class($this->getStory()) : null,
            'interaction' => $this->getInteraction() !== null ? get_class($this->getInteraction()) : null,
            'values' => $this->values,
        ];

        logger('Context.save', $value);

        cache()->put($key, $value);
    }

    public function clear(): void
    {
        logger('Context.clear');

        cache()->forget(self::key($this->channel->name(), $this->channel->participant()));
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'channel' => $this->channel,
            'participant' => $this->participant,
            'story' => $this->story,
            'interaction' => $this->interaction,
            'values' => $this->values,
        ];
    }

    private static function key(string $channel, Participant $participant): string
    {
        return 'context.' . $channel . '.' . $participant->getIdentifier();
    }

}