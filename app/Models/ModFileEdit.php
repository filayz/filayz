<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $server_id
 * @property int $mod_file_id
 * @property ModFile $file
 * @property string $contents
 * @property bool $active
 */
class ModFileEdit extends Model
{
    protected $casts = [
        'active' => 'bool'
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function file()
    {
        return $this->belongsTo(ModFile::class, 'mod_file_id');
    }
}
