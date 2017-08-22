<?php

declare(strict_types=1);

namespace FondBot\Foundation\Commands;

use FondBot\Conversation\Session;
use FondBot\Conversation\SessionManager;

class SaveSession
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function handle(SessionManager $manager): void
    {
        $manager->save($this->session);
    }
}
