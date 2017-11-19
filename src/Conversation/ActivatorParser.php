<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use InvalidArgumentException;
use FondBot\Conversation\Activators\In;
use FondBot\Conversation\Activators\Exact;
use FondBot\Conversation\Activators\Regex;
use FondBot\Conversation\Activators\Payload;
use FondBot\Contracts\Conversation\Activator;
use FondBot\Conversation\Activators\Contains;
use FondBot\Conversation\Activators\Attachment;

class ActivatorParser
{
    private static $activators = [
        'contains' => Contains::class,
        'exact' => Exact::class,
        'in' => In::class,
        'regex' => Regex::class,
        'attachment' => Attachment::class,
        'payload' => Payload::class,
    ];

    private static $arrayActivators = [
        'contains',
        'in',
        'regex',
    ];

    /**
     * @param array $data
     *
     * @return Activator[]
     */
    public static function parse(array $data): array
    {
        $result = [];

        foreach ($data as $key => $activator) {
            if ($activator instanceof Activator) {
                $result[] = $activator;

                continue;
            }

            [$name, $parameters] = explode(':', $activator, 2);

            $parameters = collect(str_getcsv($parameters));

            if (in_array($name, static::$arrayActivators, true)) {
                $value = $parameters;
            } else {
                $value = $parameters->first();
                $parameters = $parameters->slice(1)->values()->transform(function ($item) {
                    if ($item === 'true') {
                        return true;
                    }

                    if ($item === 'false') {
                        return false;
                    }

                    return $item;
                });
            }

            if (isset(static::$activators[$name])) {
                /** @var Activator $activator */
                $activator = new static::$activators[$name]($value, ...$parameters);

                $result[] = $activator;

                continue;
            }

            throw new InvalidArgumentException('Activator `'.$name.'` does not exist.');
        }

        return $result;
    }
}
