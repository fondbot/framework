<?php declare(strict_types=1);

namespace FondBot;

use FondBot\Channels\Abstracts\Driver;
use FondBot\Conversation\Abstracts\Story;
use FondBot\Conversation\Context;
use FondBot\Conversation\Launcher;
use FondBot\Database\Entities\Channel;
use FondBot\Traits\Loggable;
use Illuminate\Database\Eloquent\Collection;
use Storage;

class Bot
{

    use Loggable;

    /** @var array */
    private $stories = [];

    /** @var string */
    private $storiesDirectory = 'src/Stories';

    /** @var Channel[]|Collection */
    private $channels = [];

    /** @var array */
    private $routes = [];

    public function init(): void
    {
        $this->loadStories();
        $this->processRequest();
    }

    private function loadStories(): void
    {
        $filesystem = Storage::disk('local');

        /** @var array $files */
        $files = $filesystem->allFiles($this->storiesDirectory);

        foreach ($files as $file) {
            if ($file['type'] !== 'file' || $file['extension'] !== 'php') {
                continue;
            }

            $path = str_replace(['src/Stories/', '.php', '/'], ['', '', '\\'], $file['path']);
            $path = 'Bot\\Stories\\' . $path;

            $story = app($path);

            if (!$story instanceof Story) {
                continue;
            }

            $this->stories[] = $path;
        }
    }

    private function processRequest(): void
    {
        $request = request();

        $channelName = collect($this->routes)->search(
        /** @return bool */
            function ($route) use ($request) {
                return hash_equals($route, $request->path());
            });

        if ($channelName === false) {
            return;
        }

        /** @var Channel $entity */
        $entity = $this->channels->where('name', $channelName)->first();

        /** @var Driver $driver */
        $driver = new $entity->driver($request, $channelName, $entity->parameters);

        $driver->init();

        // Verify request
        $driver->verifyRequest();

        $context = Context::instance($driver);

        /** @var Launcher $conversation */
        $conversation = Launcher::instance($driver, $entity, $context, $this->stories);

        // Start conversation
        $conversation->start();
    }

}