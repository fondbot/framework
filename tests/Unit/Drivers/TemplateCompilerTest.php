<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers;

use Mockery\Mock;
use FondBot\Drivers\Type;
use FondBot\Tests\TestCase;
use FondBot\Contracts\Template;
use FondBot\Templates\Keyboard;
use FondBot\Drivers\TemplateCompiler;

class TemplateCompilerTest extends TestCase
{
    public function testCompile(): void
    {
        $template = Keyboard::create();
        $type = $this->mock(Type::class);

        $type->shouldReceive('toNative')->andReturn(null);

        /** @var TemplateCompiler|Mock $compiler */
        $compiler = $this->mock(TemplateCompiler::class)->shouldAllowMockingProtectedMethods()->makePartial();

        $compiler->shouldReceive('compileKeyboard')->with($template)->andReturn($type)->once();

        $result = $compiler->compile($template);
        $this->assertSame($type, $result);
    }

    public function testCompileUsingMethodButMethodDoesNotExist(): void
    {
        $template = $this->mock(Template::class);

        /** @var TemplateCompiler|Mock $compiler */
        $compiler = $this->mock(TemplateCompiler::class)->shouldAllowMockingProtectedMethods()->makePartial();

        $template->shouldReceive('getName')->andReturn('foo')->atLeast()->once();

        $this->assertNull($compiler->compile($template));
    }
}
