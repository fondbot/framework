<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Activators;

use Tests\TestCase;
use FondBot\Conversation\Activators\InArray;
use Tests\Classes\Fakes\FakeReceivedMessage;

class InArrayTest extends TestCase
{
    public function test_array_matches()
    {
        $activator = new InArray(['/bye', '/start', '/test']);
        $this->assertTrue(
            $activator->matches(new FakeReceivedMessage(null, '/start'))
        );
    }

    public function test_array_does_not_match()
    {
        $activator = new InArray(['/bye', '/start', '/test']);
        $this->assertFalse(
            $activator->matches(new FakeReceivedMessage(null, '/stop'))
        );
    }

    public function test_collection_matches()
    {
        $activator = new InArray(collect(['/bye', '/start', '/test']));
        $this->assertTrue(
            $activator->matches(new FakeReceivedMessage(null, '/start'))
        );
    }

    public function test_collection_does_not_match()
    {
        $activator = new InArray(collect(['/bye', '/start', '/test']));
        $this->assertFalse(
            $activator->matches(new FakeReceivedMessage(null, '/stop'))
        );
    }
}
