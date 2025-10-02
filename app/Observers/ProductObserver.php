<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Str;

class ProductObserver
{
    /**
     * Pastikan slug terisi & unik saat membuat produk
     */
    public function creating(Product $product): void
    {
        $this->ensureSlug($product);
    }

    /**
     * Jika nama berubah dan slug kosong/ingin disesuaikan, isi lagi.
     * (Kalau kamu tidak ingin slug berubah saat update, hapus method ini.)
     */
    public function updating(Product $product): void
    {
        // Hanya ketika slug kosong atau name berubah dan slug masih sama dengan slug dari name lama
        if (empty($product->slug) || $product->isDirty('name')) {
            $this->ensureSlug($product);
        }
    }

    private function ensureSlug(Product $product): void
    {
        $base = Str::slug($product->name ?: 'produk');
        $slug = $base;
        $i = 2;

        // Cek unik
        while (
            Product::where('slug', $slug)
                ->when($product->exists, fn($q) => $q->where('id', '!=', $product->id))
                ->exists()
        ) {
            $slug = $base.'-'.$i;
            $i++;
        }

        $product->slug = $slug;
    }
}
