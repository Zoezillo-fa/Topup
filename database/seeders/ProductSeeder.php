<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Game; // <-- TAMBAHKAN BARIS INI
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder // Ganti nama class jika nama file Anda berbeda
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

        // 2. Buat Game
        $gameML = Game::create([
            'name' => 'Mobile Legends',
            'slug' => 'mobile-legends',
            'category_id' => $catGames->id,
        ]);

        // 3. Buat Produk (Denominasi)
        Product::create([
            'game_id'      => $gameML->id,
            'denomination' => '100 Diamonds',
            'product_code' => 'ML_100',
            'supplier_sku' => 'MDL100',
            'price'        => 15000,
        ]);

        // ...tambahkan produk lain jika perlu...

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}