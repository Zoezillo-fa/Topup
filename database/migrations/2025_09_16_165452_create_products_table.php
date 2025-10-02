<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id(); // Kolom ID otomatis (angka 1, 2, 3, ...)
        $table->string('name'); // Kolom untuk nama produk (misal: 100 Diamonds)
        $table->string('product_code')->unique(); // Kolom untuk kode produk, harus unik
        $table->integer('price'); // Kolom untuk harga (dalam bentuk angka, misal: 15000)
        $table->text('description')->nullable(); // Kolom untuk deskripsi (bisa kosong)
        $table->timestamps(); // Kolom created_at dan updated_at otomatis
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
