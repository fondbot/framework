<?php

declare(strict_types=1);

namespace FondBot\Http;

use RuntimeException;
use FondBot\Helpers\Arr;
use Psr\Http\Message\ServerRequestInterface;

trait InteractsWithRequest
{
    /** @var ServerRequestInterface */
    protected $request;

    /**
     * Get request value.
     *
     * @param string|null $key
     * @param null        $default
     *
     * @return mixed
     */
    public function getRequest(string $key = null, $default = null)
    {
        if ($this->request === null) {
            return null;
        }

        try {
            $contents = json_decode($this->request->getBody()->getContents(), true);

            if ($contents === null) {
                return null;
            }

            return Arr::get($contents, $key, $default);
        } catch (RuntimeException $exception) {
            return null;
        }
    }

    /**
     * If request has key.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasRequest(string $key): bool
    {
        if ($this->request === null) {
            return false;
        }

        try {
            $contents = json_decode($this->request->getBody()->getContents(), true);

            if ($contents === null) {
                return false;
            }

            return Arr::has($contents, [$key]);
        } catch (RuntimeException $exception) {
            return false;
        }
    }

    /**
     * Get header.
     *
     * @param string $name
     * @param null   $default
     *
     * @return mixed
     */
    public function getHeader(string $name = null, $default = null)
    {
        return Arr::get($this->request->getHeaders(), $name, $default);
    }
}
