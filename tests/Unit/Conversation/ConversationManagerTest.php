<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Tests\TestCase;
use FondBot\Conversation\Intent;
use FondBot\Conversation\Session;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Conversable;
use FondBot\Conversation\Interaction;
use FondBot\Conversation\IntentManager;
use FondBot\Conversation\SessionManager;
use FondBot\Conversation\ConversationManager;

class ConversationManagerTest extends TestCase
{
    public function test_handle_new_dialog(): void
    {
        $sessionManager = $this->mock(SessionManager::class);
        $intentManager = $this->mock(IntentManager::class);
        $session = $this->mock(Session::class);
        $intent = $this->mock(Intent::class);
        $receivedMessage = $this->mock(ReceivedMessage::class);

        $this->kernel->setSession($session);

        $session->shouldReceive('getInteraction')->andReturn(null)->once();

        $intentManager->shouldReceive('find')
            ->with($receivedMessage)
            ->andReturn($intent)
            ->once();

        $session->shouldReceive('setIntent')->with($intent)->once();
        $session->shouldReceive('setInteraction')->with(null)->once();
        $session->shouldReceive('setContext')->with([])->once();
        $intent->shouldReceive('handle')->with($this->kernel)->once();
        $sessionManager->shouldReceive('close')->with($session)->once();

        (new ConversationManager($this->kernel))->handle($receivedMessage);
    }

    public function test_handle_existing_dialog(): void
    {
        $sessionManager = $this->mock(SessionManager::class);
        $intentManager = $this->mock(IntentManager::class);
        $interaction = $this->mock(Interaction::class);
        $session = $this->mock(Session::class);
        $receivedMessage = $this->mock(ReceivedMessage::class);

        $this->kernel->setSession($session);

        $session->shouldReceive('getInteraction')->andReturn($interaction)->atLeast()->once();

        $intentManager->shouldReceive('find')->never();
        $interaction->shouldReceive('handle')->with($this->kernel)->once();

        $sessionManager->shouldReceive('close')->with($session)->once();

        (new ConversationManager($this->kernel))->handle($receivedMessage);
    }

    public function test_restart_intent(): void
    {
        $intent = $this->mock(Intent::class);
        $session = $this->mock(Session::class);

        $this->kernel->setSession($session);

        $session->shouldReceive('setIntent')->with($intent)->once();
        $session->shouldReceive('setInteraction')->with(null)->once();
        $session->shouldReceive('setContext')->with([])->once();
        $intent->shouldReceive('handle')->once();

        (new ConversationManager($this->kernel))->restart($intent);
    }

    public function test_transition(): void
    {
        $conversable = $this->mock(Conversable::class);
        $conversable->shouldReceive('handle')->with($this->kernel)->once();

        (new ConversationManager($this->kernel))->transition($conversable);
    }

    public function test_restart_interaction(): void
    {
        $interaction = $this->mock(Interaction::class);
        $session = $this->mock(Session::class);

        $this->kernel->setSession($session);

        $session->shouldReceive('setInteraction')->with(null)->once();
        $session->shouldReceive('setContext')->with([])->once();
        $interaction->shouldReceive('handle')->once();

        (new ConversationManager($this->kernel))->restart($interaction);
    }
}
