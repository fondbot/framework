<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use Exception;
use Illuminate\Support\Str;

class ConversationCreator
{
    /**
     * Create new story.
     *
     * @param string $name
     * @throws Exception
     */
    public function createStory(string $name): void
    {
        $contents = file_get_contents(__DIR__.'/../../resources/stubs/Story.stub');

        $className = $this->className($name, 'Story');

        // Replace stub placeholders
        $this->replacePlaceholder($contents, 'namespace', $this->botNamespace());
        $this->replacePlaceholder($contents, 'className', $className);
        $this->replacePlaceholder($contents, 'name', $this->formatName($name));

        $path = $this->botDirectory().'/'.$this->filename($className);

        $this->write($path, $contents);
    }

    /**
     * Create new interaction.
     *
     * @param string $name
     * @throws Exception
     */
    public function createInteraction(string $name): void
    {
        $contents = file_get_contents(__DIR__.'/../../resources/stubs/Interaction.stub');

        $className = $this->className($name, 'Interaction');

        // Replace stub placeholders
        $this->replacePlaceholder($contents, 'namespace', $this->botNamespace('Interactions'));
        $this->replacePlaceholder($contents, 'className', $className);

        $path = $this->botDirectory('Interactions').'/'.$this->filename($className);

        $this->write($path, $contents);
    }

    /**
     * Replace placeholder.
     *
     * @param string $input
     * @param string $key
     * @param string $value
     */
    private function replacePlaceholder(string &$input, string $key, string $value): void
    {
        $input = str_replace('{'.$key.'}', $value, $input);
    }

    /**
     * Get filename.
     *
     * @param string $name
     *
     * @return string
     */
    private function filename(string $name): string
    {
        return $name.'.php';
    }

    /**
     * Get formatted name.
     *
     * @param string $name
     * @return string
     */
    private function formatName(string $name): string
    {
        return Str::lower(trim($name));
    }

    /**
     * Get name of class.
     *
     * @param string $name
     * @param string $postfix
     * @return string
     */
    private function className(string $name, string $postfix): string
    {
        $name = trim($name);
        if (! ends_with($name, $postfix)) {
            $name .= $postfix;
        }

        return $name;
    }

    /**
     * Get application namespace.
     *
     * @return string
     */
    private function applicationNamespace(): string
    {
        $namespace = collect(config('app.providers'))->first(function ($item) {
            return str_contains($item, ['AppServiceProvider']);
        });
        $namespace = str_replace('Providers\\AppServiceProvider', '', $namespace);

        return $namespace;
    }

    /**
     * Get application directory.
     *
     * @return string
     */
    private function applicationDirectory(): string
    {
        $composer = file_get_contents(base_path('composer.json'));
        $composer = json_decode($composer, true);
        $namespaces = array_merge($composer['autoload']['psr-0'] ?? [], $composer['autoload']['psr-4'] ?? []);

        /** @noinspection PhpUnusedParameterInspection */
        $directory = collect($namespaces)->first(function ($item, $namespace) {
            return $namespace === $this->applicationNamespace();
        });

        return $directory;
    }

    /**
     * Get bot namespace.
     *
     * @param string|null $additional
     *
     * @return string
     */
    private function botNamespace(string $additional = null): string
    {
        $namespace = $this->applicationNamespace().config('fondbot.namespace');

        if ($additional !== null) {
            $namespace .= '\\'.$additional;
        }

        return $namespace;
    }

    /**
     * Creates bot directory if not exists and returns its path.
     *
     * @param string|null $additional
     *
     * @return string
     *
     * @throws Exception
     */
    private function botDirectory(string $additional = null): string
    {
        $path = $this->applicationDirectory().config('fondbot.namespace');

        if ($additional !== null) {
            $path .= '/'.$additional;
        }

        $path = base_path($path);

        if (! @mkdir($path, 0755, true) && ! is_dir($path)) {
            throw new Exception('Could not create Bot directory.');
        }

        return $path;
    }

    /**
     * Write contents to file.
     *
     * @param string $path
     * @param string $contents
     * @throws Exception
     */
    private function write(string $path, string $contents): void
    {
        if (file_exists($path)) {
            throw new Exception('File already exists.');
        }

        file_put_contents($path, $contents);
    }
}
