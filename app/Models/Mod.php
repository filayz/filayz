<?php

namespace App\Models;

use App\Enums\LoadPriority;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $path
 * @property string $workshop_uri
 * @property Collection|Server[] $servers
 * @property Collection|ModFile[] $files
 * @property Collection|ModFile[] $editableFiles
 * @property Collection|Item[] $items
 */
class Mod extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $casts = [
        'load_priority' => LoadPriority::class,
    ];

    public function servers(): BelongsToMany
    {
        return $this->belongsToMany(Server::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(ModFile::class);
    }

    public function editableFiles()
    {
        return $this->files()
            ->where('editable', true)
            ->whereNotNull('contents');
    }

    public function path(): Attribute
    {
        return Attribute::make(
            get: fn () => config('filesystems.disks.mods.root') . "/steamapps/workshop/content/221100/$this->id"
        );
    }

    public function copyFileEdits(Server $server)
    {
        $this->files()
            ->where('editable', true)
            ->whereNotNull('contents')
            ->each(function (ModFile $file) use ($server) {

            /** @var ModFileEdit $edit */
            $edit = ModFileEdit::query()
                ->where('server_id', $server->id)
                ->where('mod_file_id', $file->id)
                ->firstOrNew([
                    'server_id' => $server->id,
                    'mod_file_id' => $file->id,
                    'contents' => $file->contents
                ]);

            $edit->active = $edit?->active ?? false;
            $edit->save();
        });
    }

    public function workshopUri(): Attribute
    {
        return Attribute::make(
            get: fn() => "https://steamcommunity.com/workshop/filedetails/?id=$this->id"
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
