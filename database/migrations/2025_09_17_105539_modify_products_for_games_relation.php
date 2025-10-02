<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'game_id')) {
                $table->foreignId('game_id')
                    ->after('id')
                    ->constrained('games')
                    ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('products', 'product_code')) {
                $table->string('product_code')->after('game_id');
            }

            if (!Schema::hasColumn('products', 'supplier_sku')) {
                $table->string('supplier_sku')->nullable()->after('product_code');
            }

            if (!Schema::hasColumn('products', 'denomination')) {
                $table->string('denomination')->nullable()->after('supplier_sku');
            }
        });

        // Isi 'denomination' dari 'name' jika ada
        if (Schema::hasColumn('products', 'name') && Schema::hasColumn('products', 'denomination')) {
            DB::table('products')
                ->whereNull('denomination')
                ->orWhere('denomination', '')
                ->update([
                    'denomination' => DB::raw('COALESCE(name, denomination)')
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'supplier_sku')) {
                $table->dropColumn('supplier_sku');
            }
            if (Schema::hasColumn('products', 'product_code')) {
                $table->dropColumn('product_code');
            }
            if (Schema::hasColumn('products', 'game_id')) {
                $table->dropConstrainedForeignId('game_id');
            }
            // Biarkan 'denomination' tetap ada agar rollback tetap aman
        });
    }
};
