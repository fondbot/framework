<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers;

use Mockery\Mock;
use FondBot\Drivers\Type;
use FondBot\Tests\TestCase;
use FondBot\Contracts\Template;
use FondBot\Templates\Keyboard;
use FondBot\Drivers\TemplateRenderer;

class TemplateRendererTest extends TestCase
{
    public function testRender(): void
    {
        $template = Keyboard::create();
        $type = $this->mock(Type::class);

        $type->shouldReceive('toNative')->andReturn(null);

        /** @var TemplateRenderer|Mock $compiler */
        $compiler = $this->mock(TemplateRenderer::class)->shouldAllowMockingProtectedMethods()->makePartial();

        $compiler->shouldReceive('renderKeyboard')->with($template)->andReturn($type)->once();

        $result = $compiler->render($template);
        $this->assertSame($type, $result);
    }

    public function testRenderUsingMethodButMethodDoesNotExist(): void
    {
        $template = $this->mock(Template::class);

        /** @var TemplateRenderer|Mock $compiler */
        $compiler = $this->mock(TemplateRenderer::class)->shouldAllowMockingProtectedMethods()->makePartial();

        $template->shouldReceive('getName')->andReturn('foo')->atLeast()->once();

        $this->assertNull($compiler->render($template));
    }
}
