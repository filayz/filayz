<?php

namespace App\Filament\Resources\ServerResource\Pages;

use App\Filament\Resources\ServerResource;
use App\Models\Server;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServer extends EditRecord
{
    protected static string $resource = ServerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('start')
                ->button()
                ->color('success')
                ->visible(fn (Server $record) => ! $record->isRunning && ! $record->requested_boot_at)
                ->action(function (Server $record) {
                    $record->requested_boot_at = Carbon::now();
                    $record->save();
                }),
            Actions\Action::make('restart')
                ->button()
                ->color('warning')
                ->visible(fn (Server $record) => $record->isRunning && ! $record->requested_restart_at)
                ->action(function (Server $record) {
                    $record->requested_restart_at = Carbon::now();
                    $record->save();
                }),
            Actions\Action::make('stop')
                ->button()
                ->color('danger')
                ->visible(fn (Server $record) => $record->isRunning && ! $record->requested_exit_at)
                ->action(function (Server $record) {
                    $record->requested_exit_at = Carbon::now();
                    $record->save();
                }),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
        ];
    }

    public function getFooterWidgetsColumns(): int|string|array
    {
        return 1;
    }
}
