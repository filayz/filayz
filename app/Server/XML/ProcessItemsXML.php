<?php

namespace App\Server\XML;

use App\Models\Item;
use App\Models\Item\Area;
use App\Models\Mission;
use App\Models\Mod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class ProcessItemsXML
{
    public function __invoke(string $path, ?Model $relatesTo = null)
    {
        $parsed = (new ParseXML($path))->parse();

        $types = Arr::get($parsed, 'type', $parsed);

        foreach ($types as $type) {
            $attributes = Arr::only($type, [
                'nominal', 'lifetime', 'restock',
                'min', 'quantmin', 'quantmax', 'cost'
            ]);

            $flags = Arr::get($type, 'flags.@attributes', []);

            $attributes = array_merge(
                $attributes,
                $flags
            );

            $attributes['name'] = Arr::get($type, '@attributes.name');

            if (empty($attributes['name'])) {
                Log::error("$path has missing name property on one of the items");
                return;
            }

            match(true) {
                $relatesTo instanceof Mod => $attributes['mod_id'] = $relatesTo->id,
                $relatesTo instanceof Mission => $attributes['mission_id'] = $relatesTo->id,
            };

            /** @var Item $item */
            $item = Item::query()->firstOrNew(
                Arr::only($attributes, ['name', 'server_id', 'mod_id', 'mission_id']),
                $attributes
            );

            $item->save();

            // Sync the areas from the file
            $areas = collect(Arr::get($type, 'usage', []))
                ->map(fn (array $usage) => Arr::get($usage, '@attributes.name'));
            $item->areas()->sync(
                Area::query()->whereIn('name', $areas)->pluck('id')
            );

            $categories = collect(Arr::get($type, 'category', []))
                ->map(fn (array $usage) => Arr::get($usage, '@attributes.name'));
            $item->categories()->sync(
                Item\Category::query()->whereIn('name', $categories)->pluck('id')
            );


        }
    }
}
