<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Contracts\Drivers;

use Mockery;
use Mockery\Mock;
use RuntimeException;
use FondBot\Tests\TestCase;
use FondBot\Contracts\Template;
use FondBot\Templates\Keyboard;
use Illuminate\Contracts\Support\Arrayable;
use FondBot\Templates\Keyboard\PayloadButton;
use FondBot\Contracts\Drivers\TemplateCompiler;

class TemplateCompilerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Mockery::getConfiguration()->allowMockingNonExistentMethods(true);
    }

    public function testCompileSelfCompiling()
    {
        /** @var Template|Mock $template */
        $template = Mockery::mock(Template::class, Arrayable::class);
        /** @var Template|Mock $innerTemplate1 */
        $innerTemplate1 = Mockery::mock(Template::class, Arrayable::class);
        /** @var Template|Mock $innerInnerTemplate1 */
        $innerInnerTemplate1 = Mockery::mock(Template::class, Arrayable::class);
        /** @var Template|Mock $innerTemplate2 */
        $innerTemplate2 = Mockery::mock(Template::class, Arrayable::class);
        /** @var Template|Mock $payloadButton */
        $payloadButton = $this->mock(PayloadButton::class);
        $args = ['foo' => 'bar'];

        $template->shouldReceive('toArray')
            ->andReturn([
                'foo' => [
                    'bar' => [
                        'x' => $innerTemplate1,
                        'y' => $innerTemplate2,
                        $payloadButton,
                    ],
                ],
            ])
            ->once();

        $innerTemplate1->shouldReceive('toArray')->andReturn(['inner_inner_template' => $innerInnerTemplate1])->once();
        $innerInnerTemplate1->shouldReceive('toArray')->andReturn(['compiled_inner_inner_template_key' => 'compiled_inner_inner_template_value'])->once();
        $innerTemplate2->shouldReceive('toArray')->andReturn(['inner_template_2_key' => 'inner_template_2_value'])->once();
        $payloadButton->shouldReceive('getName')->andReturn('payloadButton')->once();

        /** @var TemplateCompiler|Mock $compiler */
        $compiler = $this->mock(TemplateCompiler::class)->shouldAllowMockingProtectedMethods()->makePartial();

        $compiler->shouldReceive('compilePayloadButton')->with(
            $payloadButton,
            $args
        )->andReturn(['payload' => 'button'])->once();

        $result = $compiler->compile($template, $args);
        $expected = [
            'foo' => [
                'bar' => [
                    'x' => [
                        'inner_inner_template' => [
                            'compiled_inner_inner_template_key' => 'compiled_inner_inner_template_value',
                        ],
                    ],
                    'y' => [
                        'inner_template_2_key' => 'inner_template_2_value',
                    ],
                    [
                        'payload' => 'button',
                    ],
                ],
            ],
        ];

        $this->assertSame($expected, $result);
    }

    public function testCompileUsingMethod(): void
    {
        $template = $this->mock(Keyboard::class);
        $args = ['foo' => 'bar'];

        /** @var TemplateCompiler|Mock $compiler */
        $compiler = $this->mock(TemplateCompiler::class)->shouldAllowMockingProtectedMethods()->makePartial();

        $template->shouldReceive('getName')->andReturn('keyboard')->once();
        $compiler->shouldReceive('compileKeyboard')->with($template, $args)->once();

        $compiler->compile($template, $args);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage "foo" cannot be compiled.
     */
    public function testCompileUsingMethodButMethodDoesNotExist(): void
    {
        $template = $this->mock(Keyboard::class);

        /** @var TemplateCompiler|Mock $compiler */
        $compiler = $this->mock(TemplateCompiler::class)->shouldAllowMockingProtectedMethods()->makePartial();

        $template->shouldReceive('getName')->andReturn('foo')->atLeast()->once();

        $compiler->compile($template);
    }
}
