<?php

declare(strict_types=1);

namespace FondBot\Contracts;

use Illuminate\Support\Collection;

interface Template
{
    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get parameters.
     *
     * @return Collection
     */
    public function getParameters(): Collection;
}
