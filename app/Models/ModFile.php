<?php

namespace App\Models;

use App\Enums\ModFileType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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

    public function readXML(): ?array
    {
        $path = $this->full_path;

        if (! $path) return null;

        $xml = file_get_contents($path);

        $xml = Str::after($xml, "<types>") ?? $xml;
        $xml = Str::beforeLast($xml, "</types>") ?? $xml;

        // Incorrect opening tag, yes you paragon storage!
        if (Str::startsWith($xml, '</')) {
            $xml = preg_replace('~^\Q</\E([^>]+)>~', '', $xml);
        }

        $xml = trim($xml);

        $xml = str_replace("\type>", "\t</type>", $xml);

        // Incorrect closing tag, or missing one, yes you paragon storage!
        if (Str::contains($xml, 'type')
            && Str::startsWith($xml, '<type')
            && !Str::endsWith($xml, '</type>')) {
            $xml .= "\n</type>";
        }

        $xml = simplexml_load_string("<types>$xml</types>");

        return json_decode(json_encode($xml), true);
    }
}
