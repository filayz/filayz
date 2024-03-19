<?php

namespace App\Filament\Resources\ServerResource\Widgets;

use App\Filament\Resources\ServerLogResource;
use App\Models\ServerLog;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ServerLogs extends BaseWidget
{
    public int $server_id;

    public function table(Table $table): Table
    {
        return ServerLogResource::tableColumns($table, $this->server_id)
            ->query(
                ServerLog::query()
                    ->where('server_id', $this->server_id)
            );
    }
}
