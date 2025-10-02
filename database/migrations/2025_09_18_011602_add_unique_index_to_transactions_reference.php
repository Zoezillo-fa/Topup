<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'reference')) {
                // Cek dulu nama index agar tidak dobel; laravel tidak punya helper cek index,
                // jadi aman saja coba tambah—kalau sudah ada akan error. Jika khawatir, lewati langkah ini.
                $table->unique('reference', 'transactions_reference_unique');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'reference')) {
                $table->dropUnique('transactions_reference_unique');
            }
        });
    }
};
