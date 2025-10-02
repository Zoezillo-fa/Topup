<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;
use Illuminate\Support\Str;

class BackfillGameSlugSeeder extends Seeder
{
    public function run(): void
    {
        $n = 0;

        Game::orderBy('id')->chunkById(200, function ($rows) use (&$n) {
            foreach ($rows as $g) {
                if (empty($g->slug)) {
                    $base = Str::slug($g->name ?: 'game');
                    $slug = $base;
                    $i = 2;

                    while (Game::where('slug', $slug)->where('id', '!=', $g->id)->exists()) {
                        $slug = $base . '-' . $i;
                        $i++;
                    }

                    $g->slug = $slug;
                    $g->saveQuietly();
                    $n++;
                }
            }
        });

        $this->command?->info("Backfill slug game selesai. {$n} game diperbarui.");
    }
}
