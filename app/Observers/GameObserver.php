<?php

namespace App\Observers;

use App\Models\Game;
use Illuminate\Support\Str;

class GameObserver
{
    public function creating(Game $game): void
    {
        $this->ensureSlug($game);
    }

    public function updating(Game $game): void
    {
        // Kalau nama berubah atau slug kosong, generate lagi
        if (empty($game->slug) || $game->isDirty('name')) {
            $this->ensureSlug($game);
        }
    }

    private function ensureSlug(Game $game): void
    {
        $base = Str::slug($game->name ?: 'game');
        $slug = $base;
        $i = 2;

        while (
            Game::where('slug', $slug)
                ->when($game->exists, fn($q) => $q->where('id', '!=', $game->id))
                ->exists()
        ) {
            $slug = $base . '-' . $i;
            $i++;
        }

        $game->slug = $slug;
    }
}
