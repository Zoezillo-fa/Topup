<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Game;
use App\Observers\GameObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Isi slug otomatis untuk Game
        Game::observe(GameObserver::class);
    }
}
