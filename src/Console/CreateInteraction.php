<?php
declare(strict_types=1);

namespace FondBot\Console;

use Illuminate\Console\Command;

class CreateInteraction extends Command
{

    protected $signature = 'fondbot:create-interaction {name}';
    protected $description = 'Create new story interaction';

    public function handle()
    {
        $stub = file_get_contents(__DIR__ . '/../../resources/stubs/Interaction.stub');
        $stub = str_replace(
            ['{namespace}', '{className}'],
            [$this->namespace(), $this->className()],
            $stub
        );

        $path = base_path($this->directory() . '/Interactions');
        if (!@mkdir($path, 0755, true) && !is_dir($path)) {
            return;
        }

        $path = $path . '/' . $this->filename();

        if (file_exists($path)) {
            $this->error($this->className() . ' already exists.');
            return;
        }

        file_put_contents($path, $stub);

        $this->info('Interaction has been successfully created.');
    }

    private function namespace(): string
    {
        return config('fondbot.namespace') . '\\Interactions';
    }

    private function className(): string
    {
        $name = trim($this->argument('name'));
        if (!ends_with($name, 'Interaction')) {
            $name .= 'Interaction';
        }

        return $name;
    }

    private function filename(): string
    {
        return $this->className() . '.php';
    }

    private function directory(): string
    {
        $composer = file_get_contents(base_path('composer.json'));
        $composer = json_decode($composer, true);
        $namespaces = array_merge($composer['autoload']['psr-0'] ?? [], $composer['autoload']['psr-4'] ?? []);

        $applicationNamespace = collect(config('app.providers'))->first(function ($item) {
            return str_contains($item, ['AppServiceProvider']);
        });
        $applicationNamespace = str_replace('Providers\\AppServiceProvider', '', $applicationNamespace);

        $baseDirectory = collect($namespaces)->first(function ($item, $namespace) use ($applicationNamespace) {
            return $namespace === $applicationNamespace;
        });

        $directory = str_replace($applicationNamespace, '', config('fondbot.namespace'));

        return $baseDirectory . $directory;
    }

}