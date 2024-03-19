<?php

namespace App\Listeners;

use App\Enums\ModFileType;
use App\Models\Mod;
use App\Models\ModFile;
use Sabre\Xml\Service;
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
            ->each(function (ModFile $file) {
                // Parse the xml to push them into items if they don't exist yet.
                $xml = file_get_contents($file->full_path);

                $reader = new Service();

                dd($reader->parse($xml));

                $crawler = new Crawler($xml);

                $crawler->children('type')->each(fn (Crawler $node) => dd($node));
            });
    }
}
