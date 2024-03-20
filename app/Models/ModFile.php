<?php

namespace App\Models;

use App\Enums\ModFileType;
use App\Server\XML\ParseXML;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $mod_id
 * @property Mod $mod
 * @property string $path
 * @property string|null $full_path
 * @property ModFileType $type
 * @property Collection|ModFileEdit[] $edits
 * @property string $contents
 * @property bool $editable
 */
class ModFile extends Model
{
    use HasFactory;

    protected $casts = [
        'type' => ModFileType::class,
    ];

    public function mod(): BelongsTo
    {
        return $this->belongsTo(Mod::class);
    }

    public function edits(): HasMany
    {
        return $this->hasMany(ModFileEdit::class);
    }

    public function edit(Server $server): ?ModFileEdit
    {
        return $this->edits()->where('server_id', $server->id)->first();
    }

    public function fullPath(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match($this->type) {
                    ModFileType::profile => null,
                    default =>  "{$this->mod->path}/{$this->path}"
                };
            }
        );
    }
}
