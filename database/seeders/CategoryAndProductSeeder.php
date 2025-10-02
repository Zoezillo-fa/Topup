<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Game;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class CategoryAndProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Transaction::truncate();
        Product::truncate();
        Game::truncate();
        Category::truncate();

        // 1. Buat Kategori
        $catGames = Category::create(['name' => 'Game Populer', 'slug' => 'game-populer']);
        $catPulsa = Category::create(['name' => 'Pulsa & Data', 'slug' => 'pulsa-data']);
        
        // 2. Buat Game
        $gameML = Game::create([
            'name' => 'Mobile Legends',
            'slug' => 'mobile-legends',
            'thumbnail' => 'https://images.pexels.com/photos/3165335/pexels-photo-3165335.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            'description' => 'Mobile Legends: Bang Bang adalah game MOBA 5v5.',
            'category_id' => $catGames->id,
        ]);
        
        $gameFF = Game::create([
            'name' => 'Free Fire',
            'slug' => 'free-fire',
            'thumbnail' => 'https://images.pexels.com/photos/163036/mario-luigi-yoschi-figures-163036.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            'description' => 'Free Fire adalah game survival shooter.',
            'category_id' => $catGames->id,
        ]);

        $gameTelkomsel = Game::create([
            'name' => 'Telkomsel',
            'slug' => 'telkomsel',
            'thumbnail' => 'https://images.pexels.com/photos/404280/pexels-photo-404280.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
            'description' => 'Isi ulang pulsa atau paket data Telkomsel Anda.',
            'category_id' => $catPulsa->id,
        ]);

        // 3. Buat Produk (Denominasi)
        Product::create([
            'game_id'      => $gameML->id,
            'denomination' => '100 Diamonds',
            'product_code' => 'ML_100',      // <-- WAJIB ADA
            'supplier_sku' => 'MDL100',      // <-- WAJIB ADA
            'price'        => 15000,
        ]);
        Product::create([
            'game_id'      => $gameFF->id,
            'denomination' => '140 Diamonds',
            'product_code' => 'FF_140',      // <-- WAJIB ADA
            'supplier_sku' => 'FF140',       // <-- WAJIB ADA
            'price'        => 20000,
        ]);
        Product::create([
            'game_id'      => $gameTelkomsel->id,
            'denomination' => 'Pulsa 10.000',
            'product_code' => 'TSEL_10',     // <-- WAJIB ADA
            'supplier_sku' => 'tsel10',      // <-- WAJIB ADA
            'price'        => 11500,
        ]);
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}