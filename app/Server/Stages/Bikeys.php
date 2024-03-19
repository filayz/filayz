<?php

namespace App\Server\Stages;

use App\Enums\ModFileType;
use App\Models\Mod;
use App\Models\ModFile;
use App\Models\Server;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use League\Pipeline\StageInterface;

class Bikeys implements StageInterface
{
    /**
     * @param Server $payload
     * @return Server
     */
    public function __invoke($payload): Server
    {
        // Remove old keys to make sure we don't load any inactive mods.
        Process::run("find $payload->path/keys/ -type f -not -name 'dayz.bikey' -delete")->throw();

        $payload->mods()
            ->wherePivot('enabled', true)
            ->whereRelation('files', 'type', ModFileType::bikey)
            ->each(function (Mod $mod) use ($payload) {
                $mod->files
                    ->where('type', ModFileType::bikey)
                    ->each(function (ModFile $file) use ($payload) {

                        $baseName = basename($file->path);

                        // Copy bikeys under their unique id, as mods seem to ignore duplicity.
                        // Is this true? Flip Transport vs Transport 3PP.
                        copy($file->full_path, "$payload->path/keys/$baseName");
                });
            });

        return $payload;
    }
}
