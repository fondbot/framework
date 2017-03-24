<?php

declare(strict_types=1);

namespace FondBot\Contracts\Database\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int                                                         $id
 * @property string                                                      $driver
 * @property string                                                      $name
 * @property array                                                       $parameters
 * @property bool                                                        $is_enabled
 * @property \Carbon\Carbon                                              $created_at
 * @property \Carbon\Carbon                                              $updated_at
 *
 * @property-read Participant[]|\Illuminate\Database\Eloquent\Collection $participants
 *
 * @mixin \Eloquent
 */
class Channel extends Model
{
    protected $table = 'channels';

    protected $casts = [
        'parameters' => 'array',
        'is_enabled' => 'bool',
    ];

    protected $fillable = [
        'name',
        'driver',
        'parameters',
        'is_enabled',
    ];

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }
}
