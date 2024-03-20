<?php

namespace App\Server\Stages;

use App\Models\Item;
use App\Models\Server;
use Illuminate\Database\Eloquent\Builder;
use League\Pipeline\StageInterface;

class TypesXml implements StageInterface
{
    /**
     * @param Server $payload
     * @return Server
     */
    public function __invoke($payload): Server
    {
        $xml = Item::query()
            ->where(function (Builder $query) use ($payload) {
                $query
                    ->where('mission_id', $payload->mission_id)
                    ->orWhereIn('mod_id', $payload->enabledMods->pluck('id'));
            })
            ->whereNull('server_id')
            ->get()
            ->map(function (Item $item) use ($payload) {
                $override = Item::query()
                    ->where('replaces_id', $item->id)
                    ->where('server_id', $payload->id)
                    ->first();

                return $override ?? $item;
            })
            ->map(fn (Item $item) => $item->xml)
            ->join("\n");

        // Store this to the server mpmissions folder.
        file_put_contents(
            "$payload->path/mpmissions/{$payload->mission->path}/db/types.xml",
            "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<types>\n$xml\n</types>"
        );

        return $payload;
    }
}
