<?php declare(strict_types=1);

namespace FondBot\Channels;

use Illuminate\Contracts\Support\Arrayable;

class Request implements Arrayable
{

    /** @var array */
    protected $parameters;

    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    public static function create(array $parameters = []): Request
    {
        return new static($parameters);
    }

    public function toArray(): array
    {
        return [
            'form_params' => $this->parameters,
        ];
    }
}
