<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers;

use Mockery;
use Mockery\Mock;
use FondBot\Drivers\Type;
use FondBot\Tests\TestCase;
use FondBot\Contracts\Template;
use FondBot\Templates\Keyboard;
use FondBot\Drivers\TemplateCompiler;
use FondBot\Templates\Keyboard\PayloadButton;

class TemplateCompilerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Mockery::getConfiguration()->allowMockingNonExistentMethods(true);
    }

    public function testCompile(): void
    {
        $template = Keyboard::create();
        $type = $this->mock(Type::class);

//        $innerTemplate1->shouldReceive('toArray')->andReturn(['inner_inner_template' => $innerInnerTemplate1])->once();
//        $innerInnerTemplate1->shouldReceive('toArray')->andReturn(['compiled_inner_inner_template_key' => 'compiled_inner_inner_template_value'])->once();
//        $innerTemplate2->shouldReceive('toArray')->andReturn(['inner_template_2_key' => 'inner_template_2_value'])->once();
//        $payloadButton->shouldReceive('getName')->andReturn('payloadButton')->once();

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
