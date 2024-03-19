<?php

namespace App\Listeners;

use App\Models\ModFile;

class ModFileObserver
{
    public function saving(ModFile $file)
    {
        $file->editable = $file->type->editable();

        if ($file->type->editable() && $file->full_path !== null && file_exists($file->full_path)) {
            $file->contents = file_get_contents($file->full_path);
        }
    }
}
