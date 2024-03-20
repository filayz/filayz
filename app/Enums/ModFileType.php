<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum ModFileType: string implements HasLabel
{
    case bikey = 'Bikey';
    case xml_types = 'Types XML';
    case info = 'Information';
    case expansion_trader = 'Expansion trader';
    case xml_spawnable_types = 'Spawnable types XML';
    case xml_random_presets = 'Random presets XML';
    case profile = 'Profiles file';
    case vpp_admin_password = 'VPPAdminTools Credentials';

    public function editable(): bool
    {
        if ($this->name !== self::xml_types->name) return false;

        return Str::contains($this->name, 'xml')
            || $this->name === self::profile->name;
    }

    public function getLabel(): ?string
    {
        return $this->value;
    }
}
