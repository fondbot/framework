<?php

declare(strict_types=1);

namespace FondBot\Contracts\Channels\Message;

use FondBot\Contracts\Core\Arrayable;
use FondBot\Contracts\Filesystem\File;
use GuzzleHttp\Client;

class Attachment implements Arrayable
{
    public const TYPE_FILE = 'file';
    public const TYPE_IMAGE = 'photo';
    public const TYPE_AUDIO = 'audio';
    public const TYPE_VIDEO = 'video';

    protected $type;
    protected $path;
    protected $contents;

    public function __construct(string $type, string $path)
    {
        $this->type = $type;
        $this->path = $path;
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
            $this->contents = $this->getGuzzle()->get($this->path)->getBody()->getContents();
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

    private function getGuzzle(): Client
    {
        return resolve(Client::class);
    }
}
