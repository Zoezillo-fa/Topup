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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('reference')->nullable()->after('status'); // Referensi dari Tripay
            $table->string('merchant_ref')->nullable()->after('reference'); // Referensi dari kita
            $table->string('payment_method')->nullable()->after('merchant_ref');
            $table->string('pay_code')->nullable()->after('payment_method'); // Kode bayar/nomor VA
            $table->string('qr_url')->nullable()->after('pay_code'); // URL gambar QRIS
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
