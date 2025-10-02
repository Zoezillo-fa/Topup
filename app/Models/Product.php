<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Pakai 'slug' untuk route model binding
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = static::generateUniqueSlug(static::resolveDisplayName($product));
            }
        });

        static::updating(function (Product $product) {
            // Kalau field nama berubah, update slug juga (opsional: hanya jika slug kosong)
            if ($product->isDirty(static::nameColumns())) {
                $product->slug = static::generateUniqueSlug(static::resolveDisplayName($product), $product->id);
            }
        });
    }

    /**
     * Tentukan daftar kolom yang berpotensi menyimpan "nama produk".
     */
    protected static function nameColumns(): array
    {
        // Urutan prioritas:
        return ['name', 'title', 'product_name', 'game', 'label'];
    }

    /**
     * Ambil "nama tampilan" dari kolom yang tersedia.
     */
    protected static function resolveDisplayName(Product $product): string
    {
        foreach (static::nameColumns() as $col) {
            if (Schema::hasColumn($product->getTable(), $col) && !empty($product->{$col})) {
                return (string) $product->{$col};
            }
        }
        // fallback aman
        return 'produk-'.$product->getKey() ?: uniqid();
    }

    /**
     * Buat slug unik dari sebuah nama.
     */
    public static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'produk';
        $slug = $base;
        $i = 1;

        $query = static::query();
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->clone()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }
}
