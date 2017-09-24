<?php

declare(strict_types=1);

namespace FondBot\Tests\Mocks;

use FondBot\Drivers\Type;
use FondBot\Contracts\Template;

class FakeType extends Type
{
    public $template;

    public function __construct(Template $template)
    {
        $this->template = $template;
    }

    public static function create(Template $template)
    {
        return new self($template);
    }
}
