<?php

namespace App\Filament\Resources\ModResource\Pages;

use App\Filament\Resources\ModResource;
use App\Models\Mod;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMod extends EditRecord
{
    protected static string $resource = ModResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ViewAction::make()
                ->label('Workshop')
                ->url(fn (Mod $record) => $record->workshop_uri, true)
        ];
    }
}
