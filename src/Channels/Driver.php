<?php

declare(strict_types=1);

namespace FondBot\Channels;

use Illuminate\Support\Collection;
use FondBot\Drivers\TemplateRenderer;
use FondBot\Contracts\Channels\Driver as DriverContract;

abstract class Driver implements DriverContract
{
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

        return $this;
    }

    /**
     * Get template compiler instance.
     *
     * @return TemplateRenderer|null
     */
    public function getTemplateRenderer(): ?TemplateRenderer
    {
        return null;
    }
}
