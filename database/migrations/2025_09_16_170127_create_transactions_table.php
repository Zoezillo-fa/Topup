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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id(); // ID unik untuk setiap transaksi
        $table->unsignedBigInteger('product_id'); // Kolom untuk menyimpan ID produk yang dibeli
        $table->string('user_id_game'); // Kolom untuk menyimpan User ID game dari pelanggan
        $table->string('status')->default('PENDING'); // Status transaksi: PENDING, SUCCESS, FAILED
        $table->integer('total_price'); // Harga total transaksi
        $table->timestamps(); // Waktu transaksi dibuat

        // Membuat relasi/foreign key ke tabel products
        $table->foreign('product_id')->references('id')->on('products');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
