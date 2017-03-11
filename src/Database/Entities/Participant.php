<?php declare(strict_types=1);

namespace FondBot\Database\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $channel_id
 * @property string $identifier
 * @property string $name
 * @property string $username
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Channel $channel
 */
final class Participant extends AbstractEntity
{

    protected $table = 'participants';

    protected $visible = [
        'identifier',
        'name',
        'username',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'identifier',
        'name',
        'username',
    ];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

}