<?php

declare(strict_types=1);

namespace FondBot\Application;

use RuntimeException;
use Composer\Script\Event;

class Composer
{
    /**
     * Handle post-install event.
     *
     * @param Event $event
     */
    public static function postInstall(Event $event): void
    {
        $vendor = $event->getComposer()->getConfig()->get('vendor-dir');

        /** @noinspection PhpIncludeInspection */
        require_once $vendor.'/autoload.php';

        self::bootstrap($vendor);
        self::discoverAssets();
    }

    /**
     * Handle post-update event.
     *
     * @param Event $event
     */
    public static function postUpdate(Event $event): void
    {
        $vendor = $event->getComposer()->getConfig()->get('vendor-dir');

        /** @noinspection PhpIncludeInspection */
        require_once $vendor.'/autoload.php';

        self::bootstrap($vendor);
        self::discoverAssets();
    }

    private static function bootstrap(string $vendor): void
    {
        $bootstrapFile = $vendor.'/../bootstrap/app.php';
        if (!file_exists($bootstrapFile)) {
            throw new RuntimeException('Bootstrap file does not exist.');
        }

        /** @noinspection PhpIncludeInspection */
        require_once $bootstrapFile;
    }

    private static function discoverAssets()
    {
        /** @var Assets $assets */
        $assets = resolve(Assets::class);

        $assets->discover();
    }
}
