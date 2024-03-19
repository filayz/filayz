<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;

class ServiceHaltCommand extends Command
{
    protected $signature = 'app:server:halt-service';
    protected $description = 'Halts the daemon that runs servers';

    public function handle()
    {
        cache()->forever('server-service-halt', 1);

        $this->info("Halt signal fired.");

        return 0;
    }
}
