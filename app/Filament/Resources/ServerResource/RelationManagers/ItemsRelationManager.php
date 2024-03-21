<?php

namespace App\Filament\Resources\ServerResource\RelationManagers;

use App\Filament\Resources\ItemResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function table(Table $table): Table
    {
        return ItemResource::table($table);
    }
}
