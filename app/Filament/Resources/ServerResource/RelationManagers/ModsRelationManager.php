<?php

namespace App\Filament\Resources\ServerResource\RelationManagers;

use App\Filament\Resources\ModResource;
use App\Models\Mod;
use App\Models\ModFile;
use App\Models\ModFileEdit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModsRelationManager extends RelationManager
{
    protected static string $relationship = 'mods';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\ToggleColumn::make('enabled')
                    ->sortable()
                    ->afterStateUpdated(fn (bool $state, Mod $record) =>  $state ? $this->copyModFileContents($record) : null),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('enabled')
                    ->query(fn (Builder $query) => $query->where('enabled', true))
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['name', 'description'])
                    ->after(fn (Mod $record) => $this->copyModFileContents($record)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (Mod $record) => ModResource::getUrl('edit', ['record' => $record])),
                Tables\Actions\ViewAction::make('workshop')
                    ->label('Workshop')
                    ->url(
                        url: fn (Mod $record) => $record->workshop_uri,
                        shouldOpenInNewTab: true
                    ),
                Tables\Actions\DetachAction::make()
                    ->after(fn (Mod $record) => $this->disableModFileEdit($record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }

    protected function copyModFileContents(Mod $record): void
    {
        $record->copyFileEdits($this->ownerRecord);
    }

    protected function disableModFileEdit(Mod $record): void
    {
        ModFileEdit::query()
            ->where('server_id', $this->ownerRecord->id)
            ->whereIn('mod_file_id', $record->files->pluck('id'))
            ->update(['active' => false]);
    }
}
