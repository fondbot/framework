<?php

declare(strict_types=1);

namespace FondBot\Foundation;

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
     * Get all assets.
     *
     * @param string|null $type
     *
     * @return array
     */
    public function all(string $type = null): array
    {
        $file = resolve('bootstrap_path').'/assets.json';

        $contents = $this->filesystem->get($file)->read();
        $assets = json_decode($contents, true);

        if ($type !== null) {
            return collect($assets)
                ->filter(function ($_, $key) use ($type) {
                    return $key === $type;
                })
                ->flatten()
                ->toArray();
        }

        return $assets;
    }

    /**
     * Discover assets.
     */
    public function discover(): void
    {
        $file = resolve('bootstrap_path').'/assets.json';

        // Flush cached assets
        if ($this->filesystem->has($file)) {
            $this->filesystem->delete($file);
        }

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
            ->filter(function ($item) {
                return $item !== null;
            })
            ->each(function ($item) use (&$assets) {
                $type = key($item);

                $assets[$type][] = $item[$type];
            });

        $this->filesystem->put($file, json_encode($assets));
    }
}
