<?php

declare(strict_types=1);

namespace FondBot\Framework\Providers;

use Illuminate\Console\Scheduling\ScheduleRunCommand;
use Illuminate\Console\Scheduling\ScheduleFinishCommand;
use Illuminate\Foundation\Providers\ArtisanServiceProvider as BaseArtisanServiceProvider;

class ArtisanServiceProvider extends BaseArtisanServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'CacheClear' => 'command.cache.clear',
        'CacheForget' => 'command.cache.forget',
        'ClearCompiled' => 'command.clear-compiled',
        'ConfigCache' => 'command.config.cache',
        'ConfigClear' => 'command.config.clear',
        'KeyGenerate' => 'command.key.generate',
        'Migrate' => 'command.migrate',
        'MigrateFresh' => 'command.migrate.fresh',
        'MigrateReset' => 'command.migrate.reset',
        'MigrateRollback' => 'command.migrate.rollback',
        'MigrateStatus' => 'command.migrate.status',
        'PackageDiscover' => 'command.package.discover',
        'QueueFailed' => 'command.queue.failed',
        'QueueFlush' => 'command.queue.flush',
        'QueueForget' => 'command.queue.forget',
        'QueueRestart' => 'command.queue.restart',
        'QueueRetry' => 'command.queue.retry',
        'QueueWork' => 'command.queue.work',
        'RouteCache' => 'command.route.cache',
        'RouteClear' => 'command.route.clear',
        'RouteList' => 'command.route.list',
        'Seed' => 'command.seed',
        'ScheduleFinish' => ScheduleFinishCommand::class,
        'ScheduleRun' => ScheduleRunCommand::class,
        'StorageLink' => 'command.storage.link',
    ];

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $devCommands = [
        'CacheTable' => 'command.cache.table',
        'ConsoleMake' => 'command.console.make',
        'EventMake' => 'command.event.make',
        'JobMake' => 'command.job.make',
        'ListenerMake' => 'command.listener.make',
        'MigrateMake' => 'command.migrate.make',
        'ModelMake' => 'command.model.make',
        'QueueFailedTable' => 'command.queue.failed-table',
        'QueueTable' => 'command.queue.table',
        'Serve' => 'command.serve',
        'TestMake' => 'command.test.make',
        'VendorPublish' => 'command.vendor.publish',
    ];

    protected function registerClearResetsCommand(): void
    {
    }
}
