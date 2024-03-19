<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum LoadPriority: int implements HasLabel
{
    case system = 0;
    case critical = 1;
    case high = 2;
    case normal = 3;
    case low = 4;
    case lowest = 5;

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
