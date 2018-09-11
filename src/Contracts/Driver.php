<?php

declare(strict_types=1);

namespace FondBot\Contracts;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use Illuminate\Http\Request;
use FondBot\Templates\Attachment;
use Illuminate\Support\Collection;

interface Driver
{
    /**
     * Get gateway display name.
     *
     * This can be used for various system where human-friendly name is required.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get driver short name.
     *
     * This name is used as an alias for configuration.
     *
     * @return string
     */
    public function getShortName(): string;

    /**
     * Initialize driver.
     *
     * @param Collection $parameters
     *
     * @return Driver|static
     */
    public function initialize(Collection $parameters): Driver;

    /**
     * Create API client.
     *
     * @return mixed
     */
    public function createClient();

    /**
     * Get API client.
     *
     * @return mixed
     */
    public function getClient();

    /**
     * Create event based on incoming request.
     *
     * @param Request $request
     *
     * @return Event
     */
    public function createEvent(Request $request): Event;

    /**
     * Create HTTP response.
     *
     * @param Request $request
     * @param Event $event
     *
     * @return mixed
     */
    public function createResponse(Request $request, Event $event);

    /**
     * Send message.
     *
     * @param Chat $chat
     * @param User $recipient
     * @param string $text
     * @param Template|null $template
     */
    public function sendMessage(Chat $chat, User $recipient, string $text, Template $template = null): void;

    /**
     * Send attachment.
     *
     * @param Chat $chat
     * @param User $recipient
     * @param Attachment $attachment
     */
    public function sendAttachment(Chat $chat, User $recipient, Attachment $attachment): void;
}
