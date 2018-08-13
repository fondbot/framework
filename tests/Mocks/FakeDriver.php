<?php

declare(strict_types=1);

namespace FondBot\Tests\Mocks;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Channels\Driver;
use FondBot\Contracts\Event;
use Illuminate\Http\Request;
use FondBot\Contracts\Template;
use FondBot\Templates\Attachment;

class FakeDriver extends Driver
{
    /**
     * Get gateway display name.
     *
     * This can be used for various system where human-friendly name is required.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'fake';
    }

    /**
     * Create API client.
     *
     * @return mixed
     */
    public function createClient()
    {
        // TODO: Implement createClient() method.
    }

    /**
     * Create event based on incoming request.
     *
     * @param Request $request
     *
     * @return Event
     */
    public function createEvent(Request $request): Event
    {
        // TODO: Implement createEvent() method.
    }

    /**
     * Send message.
     *
     * @param Chat          $chat
     * @param User          $recipient
     * @param string        $text
     * @param Template|null $template
     */
    public function sendMessage(Chat $chat, User $recipient, string $text, Template $template = null): void
    {
        // TODO: Implement sendMessage() method.
    }

    /**
     * Send attachment.
     *
     * @param Chat       $chat
     * @param User       $recipient
     * @param Attachment $attachment
     */
    public function sendAttachment(Chat $chat, User $recipient, Attachment $attachment): void
    {
        // TODO: Implement sendAttachment() method.
    }
}
