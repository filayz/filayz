<?php

namespace App\Filament\Resources\LootboxResource\Pages;

use App\Filament\Resources\LootboxResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLootboxes extends ManageRecords
{
    protected static string $resource = LootboxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
