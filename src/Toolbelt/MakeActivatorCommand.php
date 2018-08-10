<?php

declare(strict_types=1);

namespace FondBot\Toolbelt;

use Illuminate\Console\GeneratorCommand;

class MakeActivatorCommand extends GeneratorCommand
{
    protected $name = 'fondbot:make:activator';
    protected $description = 'Create a new intent activator class';
    protected $type = 'Activator';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__.'/../../resources/stubs/Activator.stub';
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name): string
    {
        return $name;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name): string
    {
        return $this->laravel['path'].'/Activators/'.$name.'.php';
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getNamespace($name): string
    {
        return 'Activators';
    }
}
