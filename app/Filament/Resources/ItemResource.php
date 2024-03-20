<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->disabled(),
                Forms\Components\TextInput::make('description'),
                Forms\Components\Select::make('server_id')
                    ->relationship('server', 'name')
                    ->disabled()
                    ->requiredWith('replace_id'),
                Forms\Components\TextInput::make('nominal')
                    ->helperText('How many of this item can be on the map.'),
                Forms\Components\TextInput::make('lifetime')
                    ->helperText('How long the item remains in the world if not ruined.'),
                Forms\Components\TextInput::make('restock')
                    ->helperText('Once an item despawns, how long it takes for another one to appear.'),
                Forms\Components\TextInput::make('min')
                    ->helperText('The minimum amount of this item that appear on the map.'),
                Forms\Components\TextInput::make('quantmin')
                    ->helperText('The minimum amount of the item quantity the item spawns with (ammo, water).. -1 is empty, 100 is full'),
                Forms\Components\TextInput::make('quantmax')
                    ->helperText('The maximum amount of the item quantity the item spawns with (ammo, water).. -1 is empty, 100 is full'),
                Forms\Components\TextInput::make('cost')
                    ->helperText('The spawn chance of the item'),
                Forms\Components\Section::make('Where loot spawns')
                    ->columns(1)
                    ->schema([
                        Forms\Components\Select::make('areas')
                            ->maxItems(4)
                            ->multiple()
                            ->preload()
                            ->relationship('areas', 'name'),
                        Forms\Components\Select::make('categories')
                            ->multiple()
                            ->preload()
                            ->relationship('categories', 'name'),
                        Forms\Components\Select::make('tags')
                            ->multiple()
                            ->preload()
                            ->relationship('tags', 'name'),
                        Forms\Components\Select::make('tiers')
                            ->multiple()
                            ->preload()
                            ->relationship('tiers', 'name', fn (Builder $query) => $query->orderBy('name', 'asc'))
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('server.name')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('server')
                    ->relationship('server', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('mod')
                    ->relationship(
                        'mod',
                        'name',
                        fn (Builder $query) => $query->whereHas('items')
                    )
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('clone')
                    ->label('Copy for server')
                    ->visible(fn (Item $record) => $record->replaces_id === null)
                    ->form([
                        Forms\Components\Select::make('server_id')
                            ->relationship('server', 'name')
                            ->requiredWith('replace_id'),
                    ])
                    ->action(function (array $data, Item $record) {
                        $clone = $record->clone($data['server_id']);

                        redirect()->to(self::getUrl('index', [
                            'tableSearch' => $clone->name,
                            'tableFilters[server][value]' => $data['server_id']
                        ]));
                    }),
                Tables\Actions\EditAction::make()->visible(fn (Item $record) => $record->replaces_id !== null),
                Tables\Actions\DeleteAction::make()->visible(fn (Item $record) => $record->replaces_id !== null),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageItems::route('/'),
        ];
    }
}
