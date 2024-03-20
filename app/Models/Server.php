<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property bool $isRunning
 * @property int $cpu_count
 * @property int $fps_limit
 * @property int $pid
 * @property string $name
 * @property string $path
 * @property int $mission_id
 * @property Mission $mission
 * @property int $port_sftp
 * @property int $port_game
 * @property int $port_reserved
 * @property int $port_battl_eye
 * @property int $port_steam_query
 * @property int $port_rcon
 * @property string $password_rcon
 * @property string $password_admin
 * @property string $ip_address
 * @property int $player_slots
 * @property int $player_count
 * @property bool $third_person_enabled
 * @property bool $voice_enabled
 * @property bool $crosshair_enabled
 * @property bool $personal_light_enabled
 * @property int $time_day_speed
 * @property int $time_night_speed
 * @property Mod[]|Collection $mods
 * @property Mod[]|Collection $enabledMods
 * @property ModFileEdit[]|Collection $fileEdits
 * @property ServerLog[]|Collection $logs
 * @property Item[]|Collection $items
 * @property Carbon $requested_boot_at
 * @property Carbon $booted_at
 * @property Carbon $requested_exit_at
 * @property Carbon $exited_at
 * @property Carbon $requested_restart_at
 */
class Server extends Model
{
    use HasFactory;

    protected $with = [
        'mods', 'fileEdits'
    ];

    protected $withCount = [
        'enabledMods'
    ];

    protected $casts = [
        'requested_restart_at' => 'datetime',
        'requested_boot_at' => 'datetime',
        'booted_at' => 'datetime',
        'requested_exit_at' => 'datetime',
        'exited_at' => 'datetime',
        'third_person_enabled' => 'bool',
        'voice_enabled' => 'bool',
        'crosshair_enabled' => 'bool',
        'personal_light_enabled' => 'bool',
    ];

    public function mission(): BelongsTo
    {
        return $this->belongsTo(Mission::class);
    }

    public function mods(): BelongsToMany
    {
        return $this->belongsToMany(Mod::class)
            ->withPivot('enabled');
    }

    public function enabledMods(): BelongsToMany
    {
        return $this->mods()->wherePivot('enabled', true);
    }

    public function isRunning(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->pid && ($this->pid === 0 || is_dir("/proc/{$this->pid}"))
        );
    }

    public function path(): Attribute
    {
        return Attribute::make(
            get: fn () => config('filesystems.disks.servers.root') . '/' . $this->id
        );
    }

    public function fileEdits(): HasMany
    {
        return $this->hasMany(ModFileEdit::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ServerLog::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
