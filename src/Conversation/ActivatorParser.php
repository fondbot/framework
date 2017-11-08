<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use InvalidArgumentException;
use FondBot\Conversation\Activators\Exact;
use FondBot\Conversation\Activators\InArray;
use FondBot\Conversation\Activators\Pattern;
use FondBot\Contracts\Conversation\Activator;
use FondBot\Conversation\Activators\Contains;
use FondBot\Conversation\Activators\WithPayload;
use FondBot\Conversation\Activators\WithAttachment;

class ActivatorParser
{
    private $data;
    private $result = [];

    private $activators = [
        'contains' => Contains::class,
        'exact' => Exact::class,
        'in_array' => InArray::class,
        'pattern' => Pattern::class,
        'with_attachment' => WithAttachment::class,
        'with_payload' => WithPayload::class,
    ];

    public function __construct(array $data)
    {
        $this->data = $data;

        $this->parse();
    }

    protected function parse(): void
    {
        foreach ($this->data as $key => $activator) {
            if ($activator instanceof Activator) {
                $this->result[] = $activator;

                continue;
            }

            [$activator, $parameters] = explode(':', $activator, 2);

            if (isset($this->activators[$activator])) {
                $this->result[] = new $this->activators[$activator]($parameters);

                continue;
            }

            throw new InvalidArgumentException('Activator `'.$activator.'` does not exist.');
        }
    }

    /**
     * @return Activator[]
     */
    public function getResult(): array
    {
        return $this->result;
    }
}
