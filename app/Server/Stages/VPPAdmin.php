<?php

namespace App\Server\Stages;

use App\Enums\ModFileType;
use App\Models\Mod;
use App\Models\Server;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use League\Pipeline\StageInterface;

class VPPAdmin implements StageInterface
{
    /**
     * @param Server $payload
     * @return Server
     */
    public function __invoke($payload): Server
    {
        /** @var Mod $mod */
        $mod = $payload->mods()
            ->whereHas('files', fn (Builder $query) => $query->where('type', ModFileType::vpp_admin_password))
            ->first();

        if (! $mod) return $payload;

        $file = $mod->files->where('type', ModFileType::vpp_admin_password)->first();

        $path = "$payload->path/profiles/$file->path";

        // Confirm we haven't saved this yet.
        $contents = is_file($path) ? file_get_contents($path) : null;

        // Skip because we already modified this file before and VPP will hash it afterwards.
        if ($contents && ! Str::startsWith($contents, '//REMOVE')) return $payload;

        file_put_contents($path, $payload->password_admin);

        return $payload;
    }
}
