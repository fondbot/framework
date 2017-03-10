<?php declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Channels\Abstracts\Driver;
use FondBot\Conversation\Abstracts\Story;
use FondBot\Conversation\Traits\RetrievesStories;
use FondBot\Database\Entities\Channel;
use FondBot\Traits\Loggable;

class Launcher
{

    /** @var Launcher|null */
    private static $instance;

    /** @var Driver */
    protected $driver;

    /** @var Channel */
    protected $channel;

    /** @var Context */
    protected $context;

    /** @var Story[] */
    protected $stories = [];

    use Loggable, RetrievesStories;

    public function __construct(Driver $driver, Channel $channel, Context $context, array $stories)
    {
        $this->driver = $driver;
        $this->channel = $channel;
        $this->context = $context;
        $this->stories = $stories;
    }

    public static function instance(Driver $driver, Channel $channel, Context $context, array $stories): Launcher
    {
        if (self::$instance === null) {
            self::$instance = new static($driver, $channel, $context, $stories);
        }

        return self::$instance;
    }

    public function start(): void
    {
        $participant = $this->driver->participant();

        // Store Participant in database
        $this->channel->participants()->updateOrCreate([
            'identifier' => $participant->getIdentifier(),
            'name' => $participant->getName(),
            'username' => $participant->getUsername(),
        ], ['identifier' => $participant->getIdentifier()]);

        // Retrieve Story
        $story = $this->retrieveStory($this->driver->message());

        $this->debug('find', ['story' => $story]);

        // No story found
        if ($story === null) {
            return;
        }

        $this->context->setStory($story);
        $this->context->save();

        $story->run($this->context);
    }

}