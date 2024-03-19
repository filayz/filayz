<?php

namespace App\Filament\Resources\ServerResource\RelationManagers;

use App\Enums\ModFileType;
use App\Models\ModFileEdit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ModFileEditsRelationManager extends RelationManager
{
    protected static string $relationship = 'fileEdits';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Toggle::make('active'),
            Forms\Components\Textarea::make('contents')
                ->visible(fn (?ModFileEdit $edit) => $edit?->exists)
                ->autosize(),
        ])
            ->columns(1);
    }



    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('file.path')
            ->columns([
                Tables\Columns\TextColumn::make('file.mod.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('file.path')->searchable()->sortable(),
                Tables\Columns\ToggleColumn::make('active')->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('restore')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (ModFileEdit $edit) {
                        $edit->contents = $edit->file->contents;
                        $edit->save();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
