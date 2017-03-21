<?php

declare(strict_types=1);

namespace FondBot\Contracts\Channels\Message;

use GuzzleHttp\Client;

class Attachment
{
    protected $type;
    protected $path;
    protected $contents;

    public function __construct(string $type, string $path)
    {
        $this->type = $type;
        $this->path = $path;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPath(): string
    {
        return $this->path;
    }

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
}
