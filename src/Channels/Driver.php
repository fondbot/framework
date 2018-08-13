<?php

declare(strict_types=1);

namespace FondBot\Channels;

use Illuminate\Support\Collection;
use FondBot\Drivers\TemplateCompiler;
use FondBot\Contracts\Channels\Driver as DriverContract;

abstract class Driver implements DriverContract
{
    protected $client;

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
     * Get template compiler instance.
     *
     * @return TemplateCompiler|null
     */
    public function getTemplateCompiler(): ?TemplateCompiler
    {
        return null;
    }
}
