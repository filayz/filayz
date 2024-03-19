<?php

namespace App\Filament\Resources;

use App\Enums\ModFileType;
use App\Filament\Resources\ModFileResource\Pages;
use App\Filament\Resources\ModFileResource\RelationManagers;
use App\Models\ModFile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModFileResource extends Resource
{
    protected static ?string $model = ModFile::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('path')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options(ModFileType::class),
                Forms\Components\Toggle::make('editable')
                    ->disabled(),
                Forms\Components\Textarea::make('contents')
                    ->visible(fn (Forms\Get $get) => $get('editable'))
                    ->autosize()
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModFiles::route('/'),
            'create' => Pages\CreateModFile::route('/create'),
            'edit' => Pages\EditModFile::route('/{record}/edit'),
        ];
    }
}
