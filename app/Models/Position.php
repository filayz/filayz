<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property float $x
 * @property float $y
 * @property float $z
 * @property int $orientation
 * @property string $name
 */
class Position extends Model
{
    protected $casts = [
        'x' => 'float',
        'y' => 'float',
        'z' => 'float',
        'orientation' => 'float',
    ];
}
