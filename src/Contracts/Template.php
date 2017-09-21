<?php

declare(strict_types=1);

namespace FondBot\Contracts;

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
     * @return array
     */
    public function getParameters(): array;
}
