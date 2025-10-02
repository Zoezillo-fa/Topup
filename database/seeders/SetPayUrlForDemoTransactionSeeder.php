<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Transaction;

class SetPayUrlForDemoTransactionSeeder extends Seeder
{
    /**
     * Mengisi pay_url untuk SEMUA transaksi yang belum punya pay_url,
     * sehingga halaman /order/success/{id} selalu bisa men-generate QR.
     */
    public function run(): void
    {
        // Safety: pastikan kolom pay_url ada
        $tmp = new Transaction;
        $table = $tmp->getTable();

        if (!Schema::hasColumn($table, 'pay_url')) {
            $this->command?->error("Kolom pay_url belum ada. Jalankan migrasi add_pay_url_to_transactions_table terlebih dahulu.");
            return;
        }

        // Ambil semua transaksi yang belum punya pay_url
        $query = Transaction::query()
            ->where(function ($q) {
                $q->whereNull('pay_url')->orWhere('pay_url', '');
            })
            ->orderBy('id');

        $total = $query->count();
        if ($total === 0) {
            $this->command?->info("Tidak ada transaksi yang perlu diisi pay_url.");
            return;
        }

        $this->command?->info("Menyiapkan pay_url untuk {$total} transaksi...");

        $updated = 0;

        $query->chunkById(200, function ($items) use (&$updated, $table) {
            foreach ($items as $tx) {
                // Buat link invoice unik berbasis ID (stabil & mudah dilacak)
                $tx->pay_url = "https://example.com/invoice/TRX-{$tx->id}";

                // Biar lebih informatif saat demo
                if (empty($tx->payment_method)) {
                    $tx->payment_method = 'QRIS';
                }

                // Nominal fallback (pakai kolom yang tersedia)
                $nominal = $tx->total_price ?? $tx->total ?? $tx->amount ?? 15000;

                if (Schema::hasColumn($table, 'amount') && empty($tx->amount)) {
                    $tx->amount = $nominal;
                } elseif (Schema::hasColumn($table, 'total') && empty($tx->total)) {
                    $tx->total = $nominal;
                } elseif (Schema::hasColumn($table, 'total_price') && empty($tx->total_price)) {
                    $tx->total_price = $nominal;
                }

                // Expired default (30 menit dari sekarang) bila kolomnya ada & kosong
                if (Schema::hasColumn($table, 'expired_at') && empty($tx->expired_at)) {
                    $tx->expired_at = now()->addMinutes(30);
                } elseif (Schema::hasColumn($table, 'expired_time') && empty($tx->expired_time)) {
                    $tx->expired_time = now()->addMinutes(30);
                }

                $tx->save();
                $updated++;
            }
        });

        $this->command?->info("Selesai. {$updated} transaksi berhasil di-update.");
    }
}
