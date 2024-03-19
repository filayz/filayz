<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServerResource\Pages;
use App\Filament\Resources\ServerResource\RelationManagers;
use App\Models\Server;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServerResource extends Resource
{
    protected static ?string $model = Server::class;

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\TextInput::make('name'),
                Forms\Components\Select::make('mission')->options(resolve('missions'))->required(),
                Forms\Components\TextInput::make('ip_address')->ip()->required(),
                Forms\Components\TextInput::make('port_game')->integer()->default(2302)->required(),
                Forms\Components\TextInput::make('port_sftp')->integer()->default(2224)->required(),
                Forms\Components\TextInput::make('port_reserved')->integer()->default(2303),
                Forms\Components\TextInput::make('port_battl_eye')->integer()->default(2304)->required(),
                Forms\Components\TextInput::make('port_steam_query')->integer()->default(27016)->required(),
                Forms\Components\TextInput::make('port_rcon')->integer()->default(2305)->required(),
                Forms\Components\TextInput::make('fps_limit')->integer()->default(150)->minValue(60)->maxValue(300)->required(),
                Forms\Components\TextInput::make('cpu_count')->integer()->minValue(1)->maxValue(resolve('cpuCores'))->required(),
                Forms\Components\TextInput::make('player_slots')->integer()->minValue(1)->required(),
                Forms\Components\TextInput::make('password_rcon')->required(),
                Forms\Components\TextInput::make('password_admin')->maxLength(32)->required(),
                Forms\Components\Toggle::make('third_person_enabled')->default(true),
                Forms\Components\Toggle::make('voice_enabled')->default(true),
                Forms\Components\Toggle::make('crosshair_enabled')->default(true),
                Forms\Components\Toggle::make('personal_light_enabled')->default(true),

                Forms\Components\TextInput::make('time_day_speed')->integer()->default(12)->minValue(0)->maxValue(24)->required(),
                Forms\Components\TextInput::make('time_night_speed')->integer()->default(1)->minValue(1)->maxValue(64)->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                Tables\Columns\IconColumn::make('isRunning')
                    ->alignCenter()
                    ->boolean(),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('port_game'),
                Tables\Columns\TextColumn::make('enabled_mods_count')
                    ->alignCenter()
                    ->counts('enabledMods')
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
            RelationManagers\ModsRelationManager::class,
            RelationManagers\ModFileEditsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServers::route('/'),
            'create' => Pages\CreateServer::route('/create'),
            'edit' => Pages\EditServer::route('/{record}/edit'),
//            'logs' => Pages\ServerLogs::route('/{record}/logs')
        ];
    }

    /**
     * @param Server $record
     * @return array|string[]
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Game Port' => $record->port_game,
            'Mission' => $record->mission,
        ];
    }
}
