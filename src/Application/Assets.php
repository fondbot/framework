<?php

declare(strict_types=1);

namespace FondBot\Application;

use League\Flysystem\Filesystem;

class Assets
{
    public const TYPE_DRIVER = 'driver';

    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Discover assets.
     *
     * @param string|null $type
     *
     * @return array
     */
    public function discover(string $type = null): array
    {
        $files = $this->filesystem->listContents('vendor', true);

        // Find all composer.json files
        // Then find suitable assets
        $assets = [];

        collect($files)
            ->filter(function (array $file) {
                return $file['type'] === 'file' && $file['basename'] === 'composer.json';
            })
            ->transform(function ($file) {
                $contents = $this->filesystem->get($file['path'])->read();
                $manifest = json_decode($contents, true);

                if (isset($manifest['extra'], $manifest['extra']['fondbot'])) {
                    return $manifest['extra']['fondbot'];
                }

                return null;
            })
            ->filter(function ($item) use ($type) {
                if ($item === null) {
                    return false;
                }

                // Filter by type
                if ($type !== null) {
                    return key($item) === $type;
                }

                return true;
            })
            ->each(function ($item) use (&$assets) {
                $type = key($item);

                $assets[$type][] = $item[$type];
            });

        if ($type !== null) {
            $assets = collect($assets)
                ->filter(function ($_, $key) use ($type) {
                    return $key === $type;
                })
                ->values()
                ->flatten()
                ->toArray();
        }

        return $assets;
    }
}
