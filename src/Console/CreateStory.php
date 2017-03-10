<?php
declare(strict_types=1);

namespace FondBot\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateStory extends Command
{

    protected $signature = 'fondbot:create-story {name}';
    protected $description = 'Create new Story';

    public function handle()
    {
        $stub = file_get_contents(__DIR__ . '/../../resources/stubs/Story.stub');
        $stub = str_replace(
            ['{namespace}', '{className}', '{name}'],
            [$this->namespace(), $this->className(), $this->name()],
            $stub
        );

        $path = base_path($this->directory());

        if (!@mkdir($path, 0755, true) && !is_dir($path)) {
            return;
        }

        $path = base_path($this->directory()) . '/' . $this->filename();

        if (file_exists($path)) {
            $this->error($this->className() . ' already exists.');
            return;
        }

        file_put_contents($path, $stub);

        $this->info('Story has been successfully created.');
    }

    private function namespace(): string
    {
        return config('fondbot.namespace');
    }

    private function name(): string
    {
        return Str::lower(trim($this->argument('name')));
    }

    private function className(): string
    {
        $name = trim($this->argument('name'));
        if (!ends_with($name, 'Story')) {
            $name .= 'Story';
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

        $directory = str_replace($applicationNamespace, '', $this->namespace());

        return $baseDirectory . $directory;
    }

}