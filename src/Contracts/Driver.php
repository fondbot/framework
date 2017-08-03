<?php

declare(strict_types=1);

namespace FondBot\Contracts;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
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
     * Define driver default parameters.
     *
     * Example: ['token' => '', 'apiVersion' => '1.0']
     *
     * @return array
     */
    public function getDefaultParameters(): array;

    /**
     * Initialize driver.
     *
     * @param array $parameters
     *
     * @return Driver|static
     */
    public function initialize(array $parameters): Driver;

    /**
     * Get driver parameters.
     *
     * @return Collection
     */
    public function getParameters(): Collection;

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
     * @param Chat          $chat
     * @param User          $recipient
     * @param string        $text
     * @param Template|null $template
     */
    public function sendMessage(Chat $chat, User $recipient, string $text, Template $template = null): void;

    /**
     * Send attachment.
     *
     * @param Chat       $chat
     * @param User       $recipient
     * @param Attachment $attachment
     */
    public function sendAttachment(Chat $chat, User $recipient, Attachment $attachment): void;

    /**
     * Send low-level request.
     *
     * @param Chat   $chat
     * @param User   $recipient
     * @param string $endpoint
     * @param array  $parameters
     */
    public function sendRequest(Chat $chat, User $recipient, string $endpoint, array $parameters = []): void;
}
