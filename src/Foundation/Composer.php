<?php

declare(strict_types=1);

namespace FondBot\Foundation;

use Illuminate\Support\Composer as BaseComposer;

class Composer extends BaseComposer
{
    /**
     * Install a package.
     *
     * @param string   $package
     * @param callable $callback
     *
     * @return int
     */
    public function install(string $package, callable $callback): int
    {
        return $this->getProcess()
            ->setCommandLine($this->findComposer().' require '.$package)
            ->run($callback);
    }

    /**
     * Determine if package already installed.
     *
     * @param string $package
     *
     * @return bool
     */
    public function installed(string $package): bool
    {
        $manifest = $this->files->get('composer.json');
        $manifest = json_decode($manifest, true);

        return collect($manifest['require'])
            ->merge($manifest['require-dev'])
            ->keys()
            ->contains($package);
    }
}
