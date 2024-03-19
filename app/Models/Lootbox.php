<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property int $id
 * @property int $server_id
 * @property Server $server
 * @property Position[]|Collection $positions
 * @property string $name
 * @property int $key_id
 * @property Key $key
 * @property bool $allow_forcing_access
 * @property bool $shines_light
 * @property float $loot_spawn_chance (lootrandomize: 1.0 no items, 0.0 all items)
 */
class Lootbox extends Model
{
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    public function positions(): MorphToMany
    {
        return $this->morphToMany(
            Position::class,
            'positionable'
        );
    }

    public function key(): BelongsTo
    {
        return $this->belongsTo(Key::class);
    }
}
