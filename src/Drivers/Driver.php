<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use Illuminate\Support\Collection;
use FondBot\Contracts\Driver as DriverContract;

abstract class Driver implements DriverContract
{
    /** @var Collection */
    protected $parameters;

    /**
     * Get driver short name.
     *
     * This name is used as an alias for configuration.
     *
     * @return string
     */
    public function getShortName(): string
    {
        $shortName = explode('\\', get_class($this));

        return collect($shortName)->last();
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
        foreach ($this->getDefaultParameters() as $key => $value) {
            $value = $parameters->get($key, $value);

            $parameters->put($key, $value);
        }

        $this->parameters = collect($parameters);

        return $this;
    }

    /**
     * Get driver parameters.
     *
     * @return Collection
     */
    public function getParameters(): Collection
    {
        return $this->parameters;
    }
}
