<?php

declare(strict_types=1);

namespace FondBot\Contracts\Database\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $sender_id
 * @property int $receiver_id
 * @property string $text
 * @property array $parameters
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read Participant|null $sender
 * @property-read Participant|null $receiver
 *
 * @mixin \Eloquent
 */
class Message extends Model
{
    protected $table = 'messages';

    protected $casts = [
        'parameters' => 'array',
    ];

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'text',
        'parameters',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }
}
