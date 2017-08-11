<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Traits;

use FondBot\Tests\TestCase;
use FondBot\Events\MessageReceived;
use FondBot\Conversation\Traits\Authorization;

class AuthorizationTest extends TestCase
{
    public function testWithMethod()
    {
        $class = new AuthorizationTraitTestClassWithMethod();

        $this->assertFalse($class->passesAuthorization($this->mock(MessageReceived::class)));
    }

    public function testWithoutMethod()
    {
        $class = new AuthorizationTraitTestClassWithoutMethod();

        $this->assertTrue($class->passesAuthorization($this->mock(MessageReceived::class)));
    }
}

class AuthorizationTraitTestClassWithMethod
{
    use Authorization;

    /**
     * Determine if passes the authorization check.
     *
     * @param MessageReceived $message
     *
     * @return bool
     */
    public function authorize(MessageReceived $message): bool
    {
        return false;
    }
}

class AuthorizationTraitTestClassWithoutMethod
{
    use Authorization;
}
