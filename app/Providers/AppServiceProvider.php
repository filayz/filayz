<?php

namespace App\Providers;

use App\Listeners\MissionObserver;
use App\Listeners\ModFileObserver;
use App\Listeners\ModObserver;
use App\Models\Mission;
use App\Models\Mod;
use App\Models\ModFile;
use App\Server\Provisioner;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Provisioner::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        $this->app->bind('cpuCores', function () {
            return cache()->rememberForever('cpuCores', fn () => Process::run('cat /proc/cpuinfo | grep processor | wc -l')->output());
        });

        $this->app->bind('missions', function () {
            return collect(Storage::disk('missions')->directories())
                ->mapWithKeys(fn (string $dir, $_) => [basename($dir) => basename($dir)]);
        });

        Mission::observe(MissionObserver::class);
        Mod::observe(ModObserver::class);
        ModFile::observe(ModFileObserver::class);
    }
}
