<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Foundation;

use FondBot\Tests\TestCase;
use FondBot\Foundation\Kernel;
use FondBot\Foundation\Factory;
use Illuminate\Contracts\Foundation\Application;

class FactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $this->markTestIncomplete();
        $application = $this->mock(Application::class);
        $application->shouldReceive('register');
        $application->shouldReceive('singleton');
        $application->shouldReceive('make');

        $result = Factory::create($application);

        $this->assertInstanceOf(Kernel::class, $result);
        $this->assertSame($result, $result->resolve(Kernel::class));
    }
}
