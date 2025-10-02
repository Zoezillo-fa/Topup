<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Tambahkan ini agar create() bisa berjalan
    protected $fillable = ['product_id', 'user_id_game', 'status', 'total_price'];

    /**
     * Mendefinisikan bahwa satu transaksi dimiliki oleh satu produk.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
