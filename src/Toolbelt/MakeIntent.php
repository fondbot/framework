<?php

declare(strict_types=1);

namespace FondBot\Toolbelt;

use Illuminate\Console\GeneratorCommand;

class MakeIntent extends GeneratorCommand
{
    protected $name = 'fondbot:make-intent';
    protected $description = 'Create a new intent class';
    protected $type = 'Intent';

    /** {@inheritdoc} */
    protected function getStub(): string
    {
        return __DIR__.'/../../resources/stubs/Intent.stub';
    }

    /** {@inheritdoc} */
    protected function qualifyClass($name): string
    {
        return $name;
    }

    /** {@inheritdoc} */
    protected function getPath($name): string
    {
        return $this->laravel['path'].'/Intents/'.$name.'.php';
    }

    /** {@inheritdoc} */
    protected function getNamespace($name): string
    {
        return 'Intents';
    }
}
