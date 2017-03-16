<?php
declare(strict_types=1);

namespace FondBot\Database\Services;

use FondBot\Contracts\Database\Entities\Message;
use FondBot\Contracts\Database\Services\MessageService as MessageServiceContract;
use FondBot\Contracts\Database\Traits\BaseServiceMethods;

class MessageService implements MessageServiceContract
{
    use BaseServiceMethods;

    public function __construct(Message $entity)
    {
        $this->entity = $entity;
    }
}
