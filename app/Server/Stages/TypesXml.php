<?php

namespace App\Server\Stages;

use App\Enums\ModFileType;
use App\Models\Mod;
use App\Models\ModFile;
use App\Models\Server;
use Illuminate\Support\Str;
use League\Pipeline\StageInterface;

class TypesXml implements StageInterface
{
    /**
     * @param Server $payload
     * @return Server
     */
    public function __invoke($payload): Server
    {
        // Always pulling in the latest, so that we don't fill it up with bogus from disabled extensions.
        $xml = file_get_contents(config('filesystems.disks.missions.root') . "/$payload->mission/db/types.xml");

        $payload
            ->enabledMods
            ->each(function (Mod $mod) use ($payload, &$xml) {
                $mod->editableFiles
                    ->where('type', ModFileType::xml_types)
                    ->each(function (ModFile $file) use ($payload, &$xml) {
                        $inject = $file->contents;

                        if ($edit = $file->edit($payload)) {
                            $inject = $edit->contents;
                        }

                    $inject = $this->sanitize($inject);

                    $signature = sprintf(
                        '%d-%d :: %s - %s',
                        $file->mod->id,
                        $file->id,
                        $file->mod->name,
                        $file->path
                    );

                    $xml = preg_replace(
                        '~\Q</types>\E~',
                        $this->contentsWithSignature($inject, $signature) . '</types>',
                        $xml
                    );
            });
        });

        // Store this to the server mpmissions folder.
        file_put_contents(
            "$payload->path/mpmissions/$payload->mission/db/types.xml",
            $xml
        );

        return $payload;
    }

    protected function contentsWithSignature(string $xml, string $signature): string
    {
        return <<<EOM

<!-- $signature -->
$xml
<!-- / $signature -->

EOM;

    }

    private function sanitize(string $inject): ?string
    {
        if (Str::contains($inject, '<types>')) {
            return Str::between($inject, '<types>', '</types>');
        }

        return $inject;
    }
}
