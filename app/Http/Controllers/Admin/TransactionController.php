<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi.
     */
    public function index()
    {
        // Ambil semua transaksi, urutkan dari yang terbaru.
        // Gunakan 'with('product')' untuk Eager Loading agar lebih efisien.
        $transactions = Transaction::with('product')->latest()->paginate(15);

        return view('admin.transactions.index', ['transactions' => $transactions]);
    }
}