<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use stdClass;
use JsonMapper;
use FondBot\Contracts\Template;
use Illuminate\Contracts\Support\Arrayable;

abstract class Type
{
    /**
     * Create type from template.
     *
     * @param Template|Template[] $templates
     *
     * @return Type|Type[]|mixed
     */
    public static function createFromTemplate($templates)
    {
        $callback = function (Template $template) {
            $to = static::class;

            return $to::create($template);
        };

        if (!is_array($templates)) {
            return $callback($templates);
        }

        return collect($templates)->transform($callback)->toArray();
    }

    /**
     * Create type from json.
     *
     * @param stdClass|array|Arrayable $value
     * @param bool $array
     *
     * @return Type|static|stdClass|array
     * @throws \JsonMapper_Exception
     */
    public static function createFromJson($value, bool $array = false)
    {
        if ($value instanceof Arrayable) {
            $value = $value->toArray();
        }

        if (is_array($value) || is_object($value)) {
            $value = json_decode(json_encode($value));
        }

        $class = static::class;
        $mapper = new JsonMapper;

        return $array ? $mapper->mapArray($value, [], $class) : $mapper->map($value, new $class);
    }

    /**
     * Convert type to native format.
     *
     * @return mixed
     */
    public function toNative()
    {
        return null;
    }
}
