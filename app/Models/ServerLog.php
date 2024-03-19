<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $server_id
 * @property Server $server
 * @property string $contents
 * @property string $type
 * @property bool $mute
 * @property Carbon $created_at
 */
class ServerLog extends Model
{
    protected $casts = [
        'mute' => 'boolean'
    ];

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }
}
