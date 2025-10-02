<?php

namespace App\Observers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Schema;

class TransactionObserver
{
    /**
     * Dipanggil setelah record dibuat (punya ID).
     * Lengkapi field yang kosong: pay_url, payment_method, nominal, expired, dan reference.
     */
    public function created(Transaction $tx): void
    {
        $table = $tx->getTable();
        $dirty = false;

        // ===== Reference / Merchant Ref =====
        // Format unik & mudah dibaca: INVyymmdd-<id 6 digit>
        // Contoh: INV250918-000123
        $ref = 'INV' . now()->format('ymd') . '-' . str_pad((string) $tx->id, 6, '0', STR_PAD_LEFT);

        if (Schema::hasColumn($table, 'reference') && empty($tx->reference)) {
            $tx->reference = $ref;
            $dirty = true;
        }
        if (Schema::hasColumn($table, 'merchant_ref') && empty($tx->merchant_ref)) {
            $tx->merchant_ref = $ref;
            $dirty = true;
        }

        // ===== pay_url (biar QR langsung bisa dibuat dari link) =====
        if (Schema::hasColumn($table, 'pay_url') && empty($tx->pay_url)) {
            $tx->pay_url = "https://example.com/invoice/TRX-{$tx->id}";
            $dirty = true;
        }

        // ===== Payment method default (untuk demo) =====
        if (empty($tx->payment_method)) {
            $tx->payment_method = 'QRIS';
            $dirty = true;
        }

        // ===== Nominal fallback (pakai kolom yang tersedia) =====
        $nominal = $tx->total_price ?? $tx->total ?? $tx->amount ?? 15000;

        if (Schema::hasColumn($table, 'amount') && empty($tx->amount)) {
            $tx->amount = $nominal; $dirty = true;
        } elseif (Schema::hasColumn($table, 'total') && empty($tx->total)) {
            $tx->total = $nominal; $dirty = true;
        } elseif (Schema::hasColumn($table, 'total_price') && empty($tx->total_price)) {
            $tx->total_price = $nominal; $dirty = true;
        }

        // ===== Expired default 30 menit =====
        if (Schema::hasColumn($table, 'expired_at') && empty($tx->expired_at)) {
            $tx->expired_at = now()->addMinutes(30); $dirty = true;
        } elseif (Schema::hasColumn($table, 'expired_time') && empty($tx->expired_time)) {
            $tx->expired_time = now()->addMinutes(30); $dirty = true;
        }

        if ($dirty) {
            // Simpan tanpa memicu event created lagi
            $tx->saveQuietly();
        }
    }
}
