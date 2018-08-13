<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers;

use Mockery\Mock;
use FondBot\Tests\TestCase;
use FondBot\Contracts\Template;
use FondBot\Templates\Keyboard;
use FondBot\Drivers\TemplateCompiler;

class TemplateCompilerTest extends TestCase
{
    public function testCompile(): void
    {
        $template = Keyboard::make();

        /** @var TemplateCompiler|Mock $compiler */
        $compiler = $this->mock(TemplateCompiler::class)->shouldAllowMockingProtectedMethods()->makePartial();

        $compiler->shouldReceive('compileKeyboard')->with($template)->andReturn(['buttons' => ['foo', 'bar']])->once();

        $result = $compiler->compile($template);
        $this->assertSame(['buttons' => ['foo', 'bar']], $result);
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
