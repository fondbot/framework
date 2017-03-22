<?php

declare(strict_types=1);

namespace FondBot\Contracts\Channels\Message;

use GuzzleHttp\Client;
use Illuminate\Contracts\Support\Arrayable;

class Attachment implements Arrayable
{
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

    private function getGuzzle(): Client
    {
        return resolve(Client::class);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'type' => $this->type,
            'path' => $this->path,
        ];
    }
}
