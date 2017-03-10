<?php declare(strict_types=1);

namespace FondBot\Database\Entities;

use Carbon\Carbon;
use FondBot\Entities\AbstractEntity;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property int $id
 * @property string $driver
 * @property string $name
 * @property array $parameters
 * @property bool $is_enabled
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Participant[]|Collection $participants
 */
final class Channel extends AbstractEntity
{

    protected $table = 'channels';

    protected $casts = [
        'parameters' => 'array',
    ];

    protected $fillable = [
        'name',
        'driver',
        'parameters',
        'is_enabled',
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

}