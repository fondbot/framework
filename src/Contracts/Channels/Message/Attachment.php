<?php

declare(strict_types=1);

namespace FondBot\Contracts\Channels\Message;

use FondBot\Bot;
use GuzzleHttp\Client;
use FondBot\Filesystem\File;
use FondBot\Contracts\Core\Arrayable;

class Attachment implements Arrayable
{
    public const TYPE_FILE = 'file';
    public const TYPE_IMAGE = 'image';
    public const TYPE_AUDIO = 'audio';
    public const TYPE_VIDEO = 'video';

    protected $type;
    protected $path;
    protected $contents;
    protected $guzzle;

    public function __construct(string $type, string $path, Client $guzzle = null)
    {
        $this->type = $type;
        $this->path = $path;
        $this->guzzle = $guzzle ?? Bot::getInstance()->get(Client::class);
    }

    /**
     * Get attachment type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get path to the attachment.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get attachment contents.
     *
     * @return string
     */
    public function getContents(): string
    {
        if ($this->contents === null) {
            $this->contents = $this->guzzle->get($this->path)->getBody()->getContents();
        }

        return $this->contents;
    }

    /**
     * Get attachment as a file.
     *
     * @return File
     */
    public function getFile(): File
    {
        // Create temporary file
        $path = sys_get_temp_dir().'/'.uniqid('attachment', true);
        file_put_contents($path, $this->getContents());

        return new File($path);
    }

    /**
     * Get all types.
     *
     * @return array
     */
    public static function possibleTypes(): array
    {
        return [
            static::TYPE_FILE,
            static::TYPE_IMAGE,
            static::TYPE_AUDIO,
            static::TYPE_VIDEO,
        ];
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'path' => $this->path,
        ];
    }
}
