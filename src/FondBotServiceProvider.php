<?php

declare(strict_types=1);

namespace FondBot;

use SplFileInfo;
use ReflectionClass;
use Illuminate\Support\Str;
use FondBot\Conversation\Intent;
use FondBot\Channels\ChannelManager;
use Symfony\Component\Finder\Finder;
use Illuminate\Support\Facades\Route;
use FondBot\Toolbelt\MakeIntentCommand;
use Illuminate\Support\ServiceProvider;
use FondBot\Toolbelt\ListDriversCommand;
use FondBot\Toolbelt\ListIntentsCommand;
use FondBot\Toolbelt\ListChannelsCommand;
use Illuminate\Cache\Repository as Cache;
use FondBot\Toolbelt\InstallDriverCommand;
use FondBot\Toolbelt\MakeActivatorCommand;
use FondBot\Toolbelt\MakeInteractionCommand;
use FondBot\Conversation\ConversationManager;

class FondBotServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(FondBot::class, function () {
            return new FondBot();
        });

        $this->registerChannelManager();
        $this->registerConversationManager();
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeIntentCommand::class,
                MakeInteractionCommand::class,
                MakeActivatorCommand::class,
                ListDriversCommand::class,
                InstallDriverCommand::class,
                ListChannelsCommand::class,
                ListIntentsCommand::class,
            ]);
        }

        Route::prefix('fondbot')
            ->middleware('fondbot.webhook')
            ->group(function () {
                Route::get('/', 'FondBot\Foundation\Controller@index');
                Route::get('/webhook/{channel}/{secret?}', 'FondBot\Foundation\Controller@webhook')->name('fondbot.webhook');
                Route::post('/webhook/{channel}/{secret?}', 'FondBot\Foundation\Controller@webhook')->name('fondbot.webhook');
            });
    }

    private function registerChannelManager(): void
    {
        $this->app->singleton(ChannelManager::class, function () {
            $manager = new ChannelManager($this->app);

            $manager->register(
                collect(config('fondbot.channels'))
                    ->mapWithKeys(function (array $parameters, string $name) {
                        return [$name => $parameters];
                    })
                    ->toArray()
            );

            return $manager;
        });
    }

    private function registerConversationManager(): void
    {
        $this->app->singleton(ConversationManager::class, function () {
            $manager = new ConversationManager($this->app, $this->app[Cache::class]);

            $namespace = $this->app->getNamespace();

            /** @var SplFileInfo[] $files */
            $files = (new Finder())->in(config('fondbot.intents_path', []))->files();

            foreach ($files as $file) {
                $file = $namespace.str_replace(
                        ['/', '.php'],
                        ['\\', ''],
                        Str::after($file->getPathname(), app_path().DIRECTORY_SEPARATOR)
                    );

                if (is_subclass_of($file, Intent::class) && !(new ReflectionClass($file))->isAbstract()) {
                    $manager->registerIntent($file);
                }
            }

            return $manager;
        });
    }
}
