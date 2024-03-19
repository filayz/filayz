<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property int $id
 * @property float $x
 * @property float $y
 * @property float $z
 * @property int $orientation
 * @property string $name
 * @property Lootbox[]|Collection $lootboxes
 */
class Position extends Model
{
    protected $casts = [
        'x' => 'float',
        'y' => 'float',
        'z' => 'float',
        'orientation' => 'float',
    ];

    public function lootbox(): MorphOne
    {
        return $this->morphOne(Lootbox::class, 'positionable');
    }
}
