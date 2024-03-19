<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LootboxResource\Pages;
use App\Filament\Resources\LootboxResource\RelationManagers;
use App\Models\Lootbox;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LootboxResource extends Resource
{
    protected static ?string $model = Lootbox::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\Select::make('server_id')
                    ->relationship('server', 'name')
                    ->required(),
                Forms\Components\Select::make('key_id')
                    ->relationship('key', 'name')
                    ->required(),
                Forms\Components\Toggle::make('allow_forcing_access'),
                Forms\Components\Toggle::make('shines_light'),
                Forms\Components\TextInput::make('loot_spawn_chance')
                    ->helperText('The chance of each item to be in the chest, 100 means all of them, 0 means none.')
                    ->integer()
                    ->default(100)
                    ->step(1)
                    ->minValue(0)
                    ->maxValue(100)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('server.name')->searchable(),
                Tables\Columns\TextColumn::make('key.name')->searchable(),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('server')->relationship('server', 'name'),
                Tables\Filters\SelectFilter::make('key')->relationship('key', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLootboxes::route('/'),
        ];
    }
}
