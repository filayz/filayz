<?php

namespace App\Models;

use App\Server\XML\ParseXML;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $path
 * @property string $path_economy_xml
 * @property string $path_events_xml
 * @property string $path_globals_xml
 * @property string $path_types_xml
 * @property string $path_messages_xml
 * @property string $root_path
 * @property Collection|Item[] $items
 */
class Mission extends Model
{
    public function rootPath(): Attribute
    {
        return Attribute::make(
            get: fn () => config('filesystems.disks.missions.root') . '/' . $this->path
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
