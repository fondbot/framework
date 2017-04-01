<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Fallback;

use Tests\TestCase;
use FondBot\Conversation\Fallback\FallbackIntent;

/**
 * @property FallbackIntent $intent
 */
class FallbackIntentTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->intent = new FallbackIntent;
    }

    public function test_activators()
    {
        $this->assertSame([], $this->intent->activators());
    }
}
