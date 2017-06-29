<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Traits;

use FondBot\Tests\TestCase;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Traits\Authorization;

class AuthorizationTest extends TestCase
{
    public function test_with_method()
    {
        $class = new AuthorizationTraitTestClassWithMethod();

        $this->assertFalse($class->passesAuthorization($this->mock(ReceivedMessage::class)));
    }

    public function test_without_method()
    {
        $class = new AuthorizationTraitTestClassWithoutMethod();

        $this->assertTrue($class->passesAuthorization($this->mock(ReceivedMessage::class)));
    }
}

class AuthorizationTraitTestClassWithMethod
{
    use Authorization;

    /**
     * Determine if passes the authorization check.
     *
     * @param \FondBot\Drivers\ReceivedMessage $message
     *
     * @return bool
     */
    public function authorize(ReceivedMessage $message): bool
    {
        return false;
    }
}

class AuthorizationTraitTestClassWithoutMethod
{
    use Authorization;
}
