<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PositionResource\Pages;
use App\Filament\Resources\PositionResource\RelationManagers;
use App\Models\Position;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PositionResource extends Resource
{
    protected static ?string $model = Position::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\Section::make('coordinates from P')
                    ->visible(fn (Forms\Get $get) => empty($get('x')))
                    ->schema([
                        Forms\Components\TextInput::make('p')
                            ->live()
                            ->afterStateUpdated(function (?string $state, Forms\Set $set) {
                                preg_match('~Position: <(?<x>[-\d.]+), (?<y>[-\d.]+), (?<z>[-\d.]+)>[\s\S]*?Orientation: <(?<direction>[-\d.]+), ([-\d.]+), ([-\d.]+)>~', $state, $m);

                                if (isset($m['x'], $m['y'], $m['z'])) {
                                    $set('x', $m['x']);
                                    $set('y', $m['y']);
                                    $set('z', $m['z']);
                                    $set('orientation', $m['direction']);

                                    $set('p', null);
                                }
                            })
                    ]),
                Forms\Components\Section::make('coordinates raw')
                    ->visible(fn (Forms\Get $get) => empty($get('p')))
                    ->schema([
                        Forms\Components\TextInput::make('x')
                            ->step(0.000001)
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\TextInput::make('y')
                            ->step(0.000001)
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\TextInput::make('z')
                            ->step(0.000001)
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\TextInput::make('orientation')
                            ->step(0.000001)
                            ->numeric()
                            ->minValue(-180)
                            ->maxValue(180),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPositions::route('/'),
            'create' => Pages\CreatePosition::route('/create'),
            'edit' => Pages\EditPosition::route('/{record}/edit'),
        ];
    }
}
