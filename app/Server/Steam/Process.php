<?php

namespace App\Server\Steam;

use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\Process as CreateProcess;

class Process
{
    public static function make(array $args, string $path): PendingProcess
    {
        $user = static::currentUser();

        return CreateProcess::command($args)
            ->path($path)
            ->env([
                'HOME' => "/home/$user",
                'CWD' => $path,
                'USER' => $user
            ]);
    }

    protected static function currentUser(): string
    {
        return posix_getpwuid(posix_geteuid())['name'];
    }
}
