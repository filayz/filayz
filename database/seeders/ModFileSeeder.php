<?php

namespace Database\Seeders;

use App\Enums\ModFileType;
use App\Listeners\ModFileObserver;
use App\Models\ModFile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModFileSeeder extends Seeder
{
//2651195301 => LoadPriority::low, // Arma 2 Helicopters Remastered
//2906371600 => LoadPriority::low, // RedFalcon Watercraft

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mod_files')->insert([
            [
                'mod_id' => 1828439124,
                'type' => ModFileType::bikey,
                'path' => 'keys/vpp.bikey'
            ],[
                'mod_id' => 3069507753,
                'type' => ModFileType::xml_types,
                'path' => 'files/types.xml'
            ],[
                'mod_id' => 3069507753,
                'type' => ModFileType::bikey,
                'path' => 'keys/huntermods.bikey'
            ],[
                'mod_id' => 2303554682,
                'type' => ModFileType::bikey,
                'path' => 'keys/wardog.v3.bikey'
            ],[
                'mod_id' => 2303554682,
                'type' => ModFileType::xml_types,
                'path' => 'server\ data/dogtags_types.xml'
            ],[
                'mod_id' => 2819373632,
                'type' => ModFileType::bikey,
                'path' => 'keys/designful.bikey'
            ],[
                'mod_id' => 2122332595,
                'type' => ModFileType::bikey,
                'path' => 'keys/wardog.v3.bikey'
            ],[
                'mod_id' => 1832448183,
                'type' => ModFileType::bikey,
                'path' => 'keys/wardog.v3.bikey'
            ],[
                'mod_id' => 1832448183,
                'type' => ModFileType::xml_types,
                'path' => 'server\ data/types.xml'
            ],[
                'mod_id' => 2545327648,
                'type' => ModFileType::bikey,
                'path' => 'keys/dab.bikey'
            ],[
                'mod_id' => 1559212036,
                'type' => ModFileType::bikey,
                'path' => 'keys/jacob_mango_v3.bikey'
            ],[
                'mod_id' => 2291785308,
                'type' => ModFileType::bikey,
                'path' => 'keys/expansion.bikey'
            ],[
                'mod_id' => 2792984177,
                'type' => ModFileType::bikey,
                'path' => 'keys/expansion.bikey'
            ],[
                'mod_id' => 2291785437,
                'type' => ModFileType::bikey,
                'path' => 'keys/expansion.bikey'
            ],[
                'mod_id' => 2576460232,
                'type' => ModFileType::bikey,
                'path' => 'keys/expansion.bikey'
            ],[
                'mod_id' => 2828486817,
                'type' => ModFileType::bikey,
                'path' => 'keys/expansion.bikey'
            ],[
                'mod_id' => 2572328470,
                'type' => ModFileType::bikey,
                'path' => 'keys/expansion.bikey'
            ],[
                'mod_id' => 2792982069,
                'type' => ModFileType::bikey,
                'path' => 'keys/expansion.bikey'
            ],[
                'mod_id' => 2793893086,
                'type' => ModFileType::bikey,
                'path' => 'keys/expansion.bikey'
            ],[
                'mod_id' => 2116157322,
                'type' => ModFileType::bikey,
                'path' => 'keys/expansion.bikey'
            ],[
                'mod_id' => 1710977250,
                'type' => ModFileType::bikey,
                'path' => 'keys/bbp.bikey'
            ],[
                'mod_id' => 1710977250,
                'type' => ModFileType::xml_types,
                'path' => 'info/BBP_types.xml'
            ],[
                'mod_id' => 1602372402,
                'type' => ModFileType::bikey,
                'path' => 'keys/cyperevenge.bikey'
            ],[
                'mod_id' => 1602372402,
                'type' => ModFileType::bikey,
                'path' => 'keys/deerisle53.bikey'
            ],
//            [
//                'mod_id' => 2842791521,
//                'type' => ModFileType::bikey,
//                'path' => 'keys/mdc.bikey'
//            ],
            [
                'mod_id' => 2345073965,
                'type' => ModFileType::bikey,
                'path' => 'key/cj187_public.bikey'
            ],[
                'mod_id' => 2443122116,
                'type' => ModFileType::bikey,
                'path' => 'keys/snafu_weapons_v1.bikey'
            ],[
                'mod_id' => 2170927235,
                'type' => ModFileType::bikey,
                'path' => 'keys/iceblade.bikey'
            ],[
                'mod_id' => 3010267444,
                'type' => ModFileType::bikey,
                'path' => 'keys/paragon.bikey'
            ],[
                'mod_id' => 3010267444,
                'type' => ModFileType::xml_types,
                'path' => 'extras/types.xml',
            ],[
                'mod_id' => 1932611410,
                'type' => ModFileType::bikey,
                'path' => 'keys/iceblade.bikey'
            ],[
                'mod_id' => 1932611410,
                'type' => ModFileType::xml_types,
                'path' => 'info/cp_sample_canabisplus_types.xml'
            ],[
                'mod_id' => 2874295844,
                'type' => ModFileType::bikey,
                'path' => 'keys/tokyo.bikey'
            ],[
                'mod_id' => 1707653948,
                'type' => ModFileType::bikey,
                'path' => 'keys/mangokrill.bikey'
            ],[
                'mod_id' => 2792982897,
                'type' => ModFileType::bikey,
                'path' => 'keys/expansion.bikey'
            ],[
                'mod_id' => 1646187754,
                'type' => ModFileType::xml_types,
                'path' => 'xml/types.xml',
            ],[
                'mod_id' => 1646187754,
                'type' => ModFileType::bikey,
                'path' => 'keys/codelockv3.bikey'
            ],[
                'mod_id' => 1827241477,
                'type' => ModFileType::bikey,
                'path' => 'keys/hdsn.bikey'
            ],[
                'mod_id' => 2654418630,
                'type' => ModFileType::bikey,
                'path' => 'keys/bspa.bikey'
            ],[
                'mod_id' => 2018887948,
                'type' => ModFileType::xml_types,
                'path' => 'serverfiles/example_types.xml',
            ],[
                'mod_id' => 2018887948,
                'type' => ModFileType::xml_spawnable_types,
                'path' => 'serverfiles/example_cfgspawnabletypes.xml',
            ],[
                'mod_id' => 2018887948,
                'type' => ModFileType::xml_random_presets,
                'path' => 'serverfiles/example_cfgrandompresets.xml',
            ],[
                'mod_id' => 2018887948,
                'type' => ModFileType::bikey,
                'path' => 'keys/cj187_public.bikey'
            ],
        ]);

        $observer = new ModFileObserver;

        ModFile::query()->each(function (ModFile $file) use ($observer) {
            $observer->saving($file);
            $file->save();
        });
    }
}
