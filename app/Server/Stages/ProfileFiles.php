<?php

namespace App\Server\Stages;

use App\Enums\ModFileType;
use App\Models\Mod;
use App\Models\ModFile;
use App\Models\ModFileEdit;
use App\Models\Server;
use League\Pipeline\StageInterface;

class ProfileFiles implements StageInterface
{
    /**
     * @param Server $payload
     * @return Server
     */
    public function __invoke($payload): Server
    {
        $payload->mods()
            ->wherePivot('enabled', true)
            ->whereRelation('files', 'type', ModFileType::profile)
            ->each(function (Mod $mod) use ($payload) {
                $mod->files
                    ->where('editable', true)
                    ->where('type', ModFileType::profile)
                    ->each(function (ModFile $file) use ($payload) {
                        try {
                            $contents = file_get_contents("$payload->path/profiles/$file->path");
                        } catch (\ErrorException $e) {
                            // File did not exist, just skip.
                            return;
                        }

                        if (empty($file->contents) && !empty($contents)) {
                            $file->contents = $contents;
                            $file->save();
                        }

                        // First make sure an editable file exists, as profile files only
                        // get created on boot.
                        $edit = $file->edit($payload) ?? ModFileEdit::query()->newModelInstance([
                            'server_id' => $payload->id,
                            'mod_file_id' => $file->id,
                            'contents' => $file->contents,
                            'active' => false,
                        ]);

                        if ($edit->isDirty()) $edit->save();

                        if (! empty($edit->contents) && $edit->active && $edit->contents !== $contents) {
                            file_put_contents(
                                "$payload->path/profiles/$file->path",
                                $edit->contents
                            );
                        }
                    });
            });

        return $payload;
    }
}
