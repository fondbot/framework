<?php

declare(strict_types=1);

namespace FondBot\Console;

use Illuminate\Console\GeneratorCommand;

class MakeInteractionCommand extends GeneratorCommand
{
    protected $name = 'fondbot:make:interaction';
    protected $description = 'Create a new interaction class';
    protected $type = 'Interaction';

    /** {@inheritdoc} */
    protected function getStub(): string
    {
        return __DIR__.'/../../resources/stubs/Interaction.stub';
    }

    /** {@inheritdoc} */
    protected function qualifyClass($name): string
    {
        return $name;
    }

    /** {@inheritdoc} */
    protected function getPath($name): string
    {
        return $this->laravel['path'].'/Interactions/'.$name.'.php';
    }

    /** {@inheritdoc} */
    protected function getNamespace($name): string
    {
        return 'Interactions';
    }
}
