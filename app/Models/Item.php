<?php

namespace App\Models;

use App\Models\Item\Area;
use App\Models\Item\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $name
 * @property string $description
 * @property int $mod_id
 * @property Mod $mod
 * @property null|int $server_id
 * @property Server $server
 * @property Collection|Category[] $categories
 * @property Collection|Area[] $areas
 * @property Collection|Tier[] $tiers
 * @property int $nominal
 * @property int $lifetime
 * @property int $restock
 * @property int $min
 * @property int $quantmin
 * @property int $quantmax
 * @property int $cost
 */
class Item extends Model
{
    public function mod(): BelongsTo
    {
        return $this->belongsTo(Mod::class);
    }

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    public function areas(): BelongsToMany
    {
        return $this->belongsToMany(Area::class, 'item_areas');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'item_categories');
    }

    public function tiers(): BelongsToMany
    {
        return $this->belongsToMany(Tier::class, 'item_tiers');
    }
}
