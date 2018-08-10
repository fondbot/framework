<?php

declare(strict_types=1);

namespace FondBot\Contracts\Channels;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Contracts\Event;
use Illuminate\Http\Request;
use FondBot\Contracts\Template;
use FondBot\Templates\Attachment;
use Illuminate\Support\Collection;
use FondBot\Drivers\TemplateRenderer;

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
     * Get template compiler instance.
     *
     * @return TemplateRenderer|null
     */
    public function getTemplateRenderer(): ?TemplateRenderer;

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

    /**
     * Send low-level request.
     *
     * @param Chat $chat
     * @param User $recipient
     * @param string $endpoint
     * @param array $parameters
     */
    public function sendRequest(Chat $chat, User $recipient, string $endpoint, array $parameters = []): void;
}
