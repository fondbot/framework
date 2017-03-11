<?php
declare(strict_types=1);

namespace FondBot\Conversation;

use Exception;
use Illuminate\Support\Str;

class ConversationCreator
{

    public function createStory(string $name): void
    {
        $contents = file_get_contents(__DIR__ . '/../../resources/stubs/Story.stub');

        $className = $this->className($name, 'Story');

        // Replace stub placeholders
        $this->replacePlaceholder($contents, 'namespace', $this->botNamespace());
        $this->replacePlaceholder($contents, 'className', $className);
        $this->replacePlaceholder($contents, 'name', $this->formatName($name));

        $path = $this->botDirectory() . '/' . $this->filename($className);

        $this->write($path, $contents);
    }

    private function replacePlaceholder(string &$input, string $key, string $value): void
    {
        $input = str_replace('{' . $key . '}', $value, $input);
    }

    /**
     * Get filename
     *
     * @param string $name
     *
     * @return string
     */
    private function filename(string $name): string
    {
        return $name . '.php';
    }

    /**
     * Get formatted name
     *
     * @param string $name
     * @return string
     */
    private function formatName(string $name): string
    {
        return Str::lower(trim($name));
    }

    /**
     * Get name of class
     *
     * @param string $name
     * @param string $postfix
     * @return string
     */
    private function className(string $name, string $postfix): string
    {
        $name = trim($name);
        if (!ends_with($name, $postfix)) {
            $name .= $postfix;
        }

        return $name;
    }

    /**
     * Get application namespace
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
     * Get application directory
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
     * Get bot namespace
     *
     * @return string
     */
    private function botNamespace(): string
    {
        return $this->applicationNamespace() . config('fondbot.namespace');
    }

    /**
     * Creates bot directory if not exists and returns its path
     *
     * @throws Exception
     */
    private function botDirectory(): string
    {
        $path = $this->applicationDirectory() . config('fondbot.namespace');
        $path = base_path($path);

        if (!@mkdir($path, 0755, true) && !is_dir($path)) {
            throw new Exception('Could not create Bot directory.');
        }

        return $path;
    }

    /**
     * Write contents to file
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