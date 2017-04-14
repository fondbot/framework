<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Traits;

use FondBot\Tests\TestCase;
use FondBot\Conversation\Activators\Exact;
use FondBot\Conversation\Activators\InArray;
use FondBot\Conversation\Activators\Pattern;
use FondBot\Conversation\Activators\Activator;
use FondBot\Conversation\Traits\HasActivators;
use FondBot\Conversation\Activators\WithAttachment;

class HasActivatorsTest extends TestCase
{
    public function test()
    {
        $class = new HasActivatorsTraitTestClass();

        $this->assertInstanceOf(Exact::class, $class->exact($this->faker()->word));
        $this->assertInstanceOf(Pattern::class, $class->pattern($this->faker()->regexify()));
        $this->assertInstanceOf(InArray::class, $class->inArray([1, 2, 3]));
        $this->assertInstanceOf(WithAttachment::class, $class->withAttachment());
        $this->assertInstanceOf(WithAttachment::class, $class->withAttachment($this->faker()->word));
    }
}

class HasActivatorsTraitTestClass
{
    use HasActivators;

    public function __call($name, $arguments)
    {
        return $this->$name(...$arguments);
    }

    /**
     * Intent activators.
     *
     * @return Activator[]
     */
    public function activators(): array
    {
        return [];
    }
}
