<?php

namespace App\Filament\Resources;

use App\Enums\LoadPriority;
use App\Filament\Resources\ModResource\Pages;
use App\Filament\Resources\ModResource\RelationManagers;
use App\Models\Mod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModResource extends Resource
{
    protected static ?string $model = Mod::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')->integer()->required(),
                Forms\Components\TextInput::make('name'),
                Forms\Components\TextInput::make('image')->nullable(),
                Forms\Components\TextInput::make('description')->nullable(),
                Forms\Components\Select::make('load_priority')
                    ->options(LoadPriority::class)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('load_priority')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make()
                    ->name('workshop')
                    ->url(fn (Mod $record) => $record->workshop_uri, true),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\FilesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMods::route('/'),
            'create' => Pages\CreateMod::route('/create'),
            'edit' => Pages\EditMod::route('/{record}/edit'),
        ];
    }

public static function getGloballySearchableAttributes(): array
{
    return ['name', 'id'];
}
}
