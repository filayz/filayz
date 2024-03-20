<?php

namespace App\Listeners;

use App\Enums\ModFileType;
use App\Models\Item;
use App\Models\Item\Area;
use App\Models\Mod;
use App\Models\ModFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class ModObserver
{
    public function saving(Mod $mod)
    {
        $workshopUri = "https://steamcommunity.com/workshop/filedetails/?id={$mod->id}";

        $contents = file_get_contents($workshopUri);

        $crawler = new Crawler($contents);

        $mod->name = $crawler->filter('.workshopItemTitle')->text();
        try {
            $mod->image = $crawler->filter('#previewImageMain')->attr('src');
        } catch (\InvalidArgumentException $e) {}

        $mod->description = $crawler->filter('.workshopItemDescription')->text();

        // files
        $mod->files()
            ->where('type', ModFileType::xml_types)
            ->each(function (ModFile $file) use ($mod) {
                $types = $file->readXML();

                $types = Arr::get($types, 'type', $types);

                foreach ($types as $type) {
                    $attributes = Arr::only($type, [
                        'nominal', 'lifetime', 'restock',
                        'min', 'quantmin', 'quantmax', 'cost'
                    ]);

                    $attributes['name'] = Arr::get($type, '@attributes.name');

                    $attributes['mod_id'] = $mod->id;

                    /** @var Item $item */
                    $item = $mod->items()->firstOrNew(
                        Arr::only($attributes, ['mod_id', 'name', 'server_id']),
                        $attributes
                    );

                    $item->save();

                    // Sync the areas from the file
                    $areas = collect(Arr::get($type, 'usage', []))
                        ->map(fn (array $usage) => Arr::get($usage, '@attributes.name'));

                    $item->areas()->sync(
                        Area::query()->whereIn('name', $areas)->pluck('id')
                    );
                }
            });
    }
}
