<?php

declare(strict_types=1);

namespace FondBot\Foundation\Providers;

use ReflectionClass;
use Illuminate\Support\Str;
use FondBot\Conversation\Intent;
use FondBot\Framework\Application;
use Symfony\Component\Finder\Finder;
use Illuminate\Support\ServiceProvider;
use FondBot\Conversation\FallbackIntent;
use Symfony\Component\Finder\SplFileInfo;
use FondBot\Contracts\Conversation\Manager;
use FondBot\Conversation\ConversationManager;
use Illuminate\Contracts\Cache\Repository as Cache;

/**
 * @property Application $app
 */
class ConversationServiceProvider extends ServiceProvider
{
    protected $intents = [];
    protected $fallbackIntent = FallbackIntent::class;

    /**
     * Register application services.
     */
    public function register(): void
    {
        $this->registerManager();
    }

    /**
     * Boot application services.
     */
    public function boot(): void
    {
        /** @var Manager $manager */
        $manager = $this->app['conversation'];

        foreach ($this->intents as $intent) {
            $manager->registerIntent($intent);
        }

        $manager->registerFallbackIntent($this->fallbackIntent);

        if (method_exists($this, 'configure')) {
            $this->configure();
        }
    }

    /**
     * Load intents from path.
     *
     * @param string $path
     */
    protected function load(string $path): void
    {
        $namespace = $this->app->getNamespace();

        /** @var SplFileInfo[] $files */
        $files = (new Finder)->in($path)->files();

        foreach ($files as $file) {
            $file = $namespace.str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($file->getPathname(), app_path().DIRECTORY_SEPARATOR)
                );

            if (is_subclass_of($file, Intent::class) && !(new ReflectionClass($file))->isAbstract()) {
                $this->app['conversation']->registerIntent($file);
            }
        }
    }

    private function registerManager(): void
    {
        $this->app->singleton('conversation', function () {
            return new ConversationManager($this->app, $this->app[Cache::class]);
        });

        $this->app->alias('conversation', Manager::class);
    }
}
