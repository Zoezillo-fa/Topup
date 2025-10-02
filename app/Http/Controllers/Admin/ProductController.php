<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; // <-- PENTING: Impor Model Product

class ProductController extends Controller
{
    public function index()
    {
        // Ambil semua produk, urutkan dari yang paling baru dibuat
        $products = Product::latest()->get();

        // Tampilkan view dan kirim data products ke dalamnya
        return view('admin.products.index', ['products' => $products]);
    }

    // Method untuk menampilkan halaman form tambah produk
    public function create()
    {
        return view('admin.products.create');
    }

    // Method untuk memvalidasi dan menyimpan produk baru
    public function store(Request $request)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_code' => 'required|string|unique:products,product_code', // harus unik di tabel products
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        // 2. Jika validasi berhasil, buat produk baru
        Product::create($validated);

        // 3. Arahkan kembali ke halaman daftar produk dengan pesan sukses
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit produk.
     */
    public function edit(Product $product)
    {
        // Laravel otomatis menemukan produk berdasarkan ID di URL (Route Model Binding)
        return view('admin.products.edit', ['product' => $product]);
    }

    /**
     * Memproses update data produk.
     */
    public function update(Request $request, Product $product)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Aturan unique diubah agar mengabaikan product_code dari produk yang sedang diedit
            'product_code' => 'required|string|unique:products,product_code,' . $product->id,
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        // 2. Jika validasi berhasil, update produk
        $product->update($validated);

        // 3. Arahkan kembali ke halaman daftar produk dengan pesan sukses
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Menghapus produk dari database.
     */
    public function destroy(Product $product)
    {
        // Hapus produk yang dipilih
        $product->delete();

        // Arahkan kembali ke halaman daftar produk dengan pesan sukses
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}