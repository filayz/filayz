<?php

namespace App\Public\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Welcome extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.welcome';

    protected static ?string $slug = '/';

    protected static ?string $navigationLabel = 'home';

    public function getTitle(): string|Htmlable
    {
        return 'Match.gs, the DayZ Deer Isle server';
    }
}
