<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $server_id
 * @property Server $server
 * @property int $position_id
 * @property Position $position
 * @property string $name
 * @property string $key
 * @property bool $allow_forcing_access
 * @property bool $light
 * @property float $loot_spawn_chance (lootrandomize: 1.0 no items, 0.0 all items)
 */
class Lootbox extends Model
{
}
