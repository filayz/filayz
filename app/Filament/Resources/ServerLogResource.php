<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServerLogResource\Pages;
use App\Models\ServerLog;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ServerLogResource extends Resource
{
    protected static ?string $model = ServerLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('controls')
                    ->columns(1)
                    ->schema([
                        Toggle::make('mute')
                            ->helperText('Muting will ignore future duplicates.')
                    ]),
                Section::make('logs')
                    ->schema([
                        ViewField::make('contents')
                            ->view('servers.log-entry')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return static::tableColumns($table);
    }

    public static function tableColumns(Table $table, int $serverSelected = null)
    {
        return $table
            ->poll('30s')
            ->deferLoading()
            ->striped()
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('server.name')
                    ->sortable()
                    ->searchable()
                    ->hidden($serverSelected !== null),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\ToggleColumn::make('mute')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('contents')
                    ->limit(40)
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(['error', 'log']),
                Tables\Filters\SelectFilter::make('server.name')
                    ->relationship('server', 'name')
                    ->hidden($serverSelected !== null),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view')
                    ->modal()
                    ->modalContent(fn (ServerLog $record) => view('servers.log-entry', ['contents' => $record->contents]))
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('delete')
                    ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each->delete()),
            ])
            ->headerActions([
                Action::make('truncate')
                    ->requiresConfirmation()
                    ->action(fn () => ServerLog::query()->truncate())
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
            'index' => Pages\ListServerLogs::route('/'),
        ];
    }
}
