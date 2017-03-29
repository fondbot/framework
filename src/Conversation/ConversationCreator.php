<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use Exception;
use RuntimeException;
use FondBot\Helpers\Str;

class ConversationCreator
{
    /**
     * Create new story.
     *
     * @param string $directory
     * @param string $namespace
     * @param string $name
     */
    public function createStory(string $directory, string $namespace, string $name): void
    {
        $contents = file_get_contents(__DIR__.'/../../resources/stubs/Story.stub');

        $className = $this->className($name, 'Story');

        // Replace stub placeholders
        $this->replacePlaceholder($contents, 'namespace', $namespace);
        $this->replacePlaceholder($contents, 'className', $className);
        $this->replacePlaceholder($contents, 'name', $this->formatName($name));

        $path = $directory.'/'.$this->filename($className);

        $this->write($path, $contents);
    }

    /**
     * Create new interaction.
     *
     * @param string $directory
     * @param string $namespace
     * @param string $name
     */
    public function createInteraction(string $directory, string $namespace, string $name): void
    {
        $contents = file_get_contents(__DIR__.'/../../resources/stubs/Interaction.stub');

        $className = $this->className($name, 'Interaction');

        // Replace stub placeholders
        $this->replacePlaceholder($contents, 'namespace', $namespace.'\\Interactions.');
        $this->replacePlaceholder($contents, 'className', $className);

        $path = $directory.'/Interactions/'.$this->filename($className);

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
        $key = mb_strtoupper($key);
        $input = str_replace('___'.$key.'___', $value, $input);
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
     *
     * @return string
     */
    private function formatName(string $name): string
    {
        return mb_strtolower(trim($name));
    }

    /**
     * Get name of class.
     *
     * @param string $name
     * @param string $postfix
     *
     * @return string
     */
    private function className(string $name, string $postfix): string
    {
        $name = trim($name);
        if (!Str::endsWith($name, $postfix)) {
            $name .= $postfix;
        }

        return $name;
    }

    /**
     * Write contents to file.
     *
     * @param string $path
     * @param string $contents
     *
     * @throws Exception
     */
    private function write(string $path, string $contents): void
    {
        if (file_exists($path)) {
            throw new RuntimeException('File already exists.');
        }

        file_put_contents($path, $contents);
    }
}
