<?php

namespace App\Models;

use App\Models\Item\Area;
use App\Models\Item\Category;
use App\Models\Item\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $mod_id
 * @property Mod $mod
 * @property null|int $server_id
 * @property Server $server
 * @property null|int $replaces_id
 * @property Item $replaces
 * @property Collection|Category[] $categories
 * @property Collection|Area[] $areas
 * @property Collection|Tier[] $tiers
 * @property Collection|Tag[] $tags
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

    public function replaces(): BelongsTo
    {
        return $this->belongsTo(self::class);
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

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'item_tags');
    }

    public function clone(int $server_id): self
    {
        /** @var self $exists */
        if ($exists = self::query()
            ->where('replaces_id', $this->id)
            ->where('server_id', $server_id)
            ->first()
        ) {
            return $exists;
        }

        $cloned = $this->replicate();

        $cloned->server_id = $server_id;
        $cloned->replaces_id = $this->id;

        $cloned->save();

        $this->relations = [];

        $this->load('areas', 'categories', 'tiers', 'mod');

        foreach ($this->relations as $name => $values) {
            match (true) {
                method_exists($cloned->{$name}(), 'sync') => $cloned->{$name}()->sync($values),
                method_exists($cloned->{$name}(), 'associate') => $cloned->{$name}()->associate($values),
            };
        }

        return $cloned;
    }
}
