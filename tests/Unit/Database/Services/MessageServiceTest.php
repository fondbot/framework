<?php

declare(strict_types=1);

namespace Tests\Unit\Database\Services;

use Tests\TestCase;
use FondBot\Database\Services\MessageService;
use FondBot\Contracts\Database\Entities\Message;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * @property Message        $message
 * @property MessageService service
 */
class MessageServiceTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        $this->service = new MessageService(
            $this->message = resolve(Message::class)
        );
    }

    public function test()
    {
        $this->assertInstanceOf(Message::class, $this->service->getEntity());
    }
}
