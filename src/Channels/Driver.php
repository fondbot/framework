<?php

declare(strict_types=1);

namespace FondBot\Channels;

use Illuminate\Support\Str;
use FondBot\Contracts\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use FondBot\Drivers\TemplateCompiler;
use FondBot\Contracts\Channels\Driver as DriverContract;

abstract class Driver implements DriverContract
{
    protected $client;
    protected $templateCompiler;

    public function __construct(TemplateCompiler $templateCompiler = null)
    {
        $this->templateCompiler = $templateCompiler;
    }

    /**
     * Get driver short name.
     *
     * This name is used as an alias for configuration.
     *
     * @return string
     */
    public function getShortName(): string
    {
        return class_basename($this);
    }

    /**
     * Initialize driver.
     *
     * @param Collection $parameters
     *
     * @return Driver|DriverContract|static
     */
    public function initialize(Collection $parameters): DriverContract
    {
        $parameters->each(function ($value, $key) {
            $key = Str::camel($key);
            $this->$key = $value;
        });

        $this->client = $this->createClient();

        return $this;
    }

    /**
     * Get API client.
     *
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Create HTTP response.
     *
     * @param Request $request
     * @param Event $event
     *
     * @return mixed
     */
    public function createResponse(Request $request, Event $event)
    {
        return [];
    }
}
