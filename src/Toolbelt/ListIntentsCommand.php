<?php

declare(strict_types=1);

namespace FondBot\Toolbelt;

use Illuminate\Console\Command;
use FondBot\Conversation\ConversationManager;

class ListIntentsCommand extends Command
{
    protected $signature = 'fondbot:intent:list';
    protected $description = 'List all registered intents';

    private $conversationManager;

    public function __construct(ConversationManager $conversationManager)
    {
        parent::__construct();

        $this->conversationManager = $conversationManager;
    }

    public function handle(): void
    {
        $rows = collect($this->conversationManager->getIntents())
            ->transform(function ($item) {
                return [$item];
            })
            ->toArray();

        $this->table(['Class'], $rows);
    }
}
