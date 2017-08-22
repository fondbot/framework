<?php

declare(strict_types=1);

namespace FondBot\Tests\Mocks;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Drivers\Driver;
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
     * Define driver default parameters.
     *
     * Example: ['token' => '', 'apiVersion' => '1.0']
     *
     * @return array
     */
    public function getDefaultParameters(): array
    {
        return ['token' => str_random()];
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

    /**
     * Send low-level request.
     *
     * @param Chat   $chat
     * @param User   $recipient
     * @param string $endpoint
     * @param array  $parameters
     */
    public function sendRequest(Chat $chat, User $recipient, string $endpoint, array $parameters = []): void
    {
        // TODO: Implement sendRequest() method.
    }
}
