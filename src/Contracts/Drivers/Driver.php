<?php

declare(strict_types=1);

namespace FondBot\Contracts\Drivers;

use FondBot\Contracts\Channels\User;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Contracts\Channels\OutgoingMessage;
use FondBot\Contracts\Channels\ReceivedMessage;
use FondBot\Channels\Exceptions\InvalidChannelRequest;

interface Driver
{
    /**
     * Set driver data.
     *
     * @param array $parameters
     * @param array $request
     * @param array $headers
     */
    public function fill(array $parameters, array $request = [], array $headers = []): void;

    /**
     * Get request value.
     *
     * @param string|null $key
     *
     * @return mixed
     */
    public function getRequest(string $key = null);

    /**
     * If request has key.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasRequest(string $key): bool;

    /**
     * Get all headers.
     *
     * @return array
     */
    public function getHeaders(): array;

    /**
     * Get header.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getHeader(string $name);

    /**
     * Get parameter value.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getParameter(string $name);

    /**
     * Configuration parameters.
     *
     * @return array
     */
    public function getConfig(): array;

    /**
     * Verify incoming request data.
     *
     * @throws InvalidChannelRequest
     */
    public function verifyRequest(): void;

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser(): User;

    /**
     * Get message received from sender.
     *
     * @return ReceivedMessage
     */
    public function getMessage(): ReceivedMessage;

    /**
     * Send reply to participant.
     *
     * @param User          $sender
     * @param string        $text
     * @param Keyboard|null $keyboard
     *
     * @return OutgoingMessage
     */
    public function sendMessage(User $sender, string $text, Keyboard $keyboard = null): OutgoingMessage;
}
