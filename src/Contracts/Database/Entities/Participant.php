<?php

declare(strict_types=1);

namespace FondBot\Contracts\Database\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int            $id
 * @property int            $channel_id
 * @property string         $identifier
 * @property string         $name
 * @property string         $username
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property Channel        $channel
 *
 * @mixin \Eloquent
 */
class Participant extends Model
{
    protected $table = 'participants';

    protected $fillable = [
        'channel_id',
        'identifier',
        'name',
        'username',
    ];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }
}
