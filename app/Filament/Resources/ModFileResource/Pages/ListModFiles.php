<?php

namespace App\Filament\Resources\ModFileResource\Pages;

use App\Filament\Resources\ModFileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListModFiles extends ListRecords
{
    protected static string $resource = ModFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
