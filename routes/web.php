<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopUpController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\ProfileController;
use App\Models\Game;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Perubahan utama:
| - /order/{game:slug}     => tampilkan form order berbasis SLUG game
| - /order/{id} (angka)    => kompatibilitas lama, redirect 301 ke slug
| - /order/success/{transaction} tetap sama
|
*/

// --- RUTE UNTUK PENGGUNA PUBLIK (TAMU) ---

// Halaman utama (menampilkan galeri produk/game)
Route::get('/', [TopUpController::class, 'index'])->name('home');

/**
 * ORDER (slug-based)
 * - showOrderForm: GET /order/{game:slug}
 * - order (POST):  POST /order
 * - success:       GET /order/success/{transaction}
 */

// Redirect lama: /order/1  ->  /order/<slug>
Route::get('/order/{id}', function (int $id) {
    $game = Game::findOrFail($id);
    return redirect()->route('order.create', ['game' => $game->slug], 301);
})->whereNumber('id');

// Form order pakai SLUG (implicit binding ke kolom "slug")
Route::get('/order/{game:slug}', [TopUpController::class, 'showOrderForm'])->name('order.create');

// Submit order (tetap)
Route::post('/order', [TopUpController::class, 'order'])->name('order.store');

// Halaman instruksi pembayaran (tetap)
Route::get('/order/success/{transaction}', [TopUpController::class, 'success'])->name('order.success');


// --- RUTE UNTUK PENGGUNA YANG SUDAH LOGIN ---
Route::middleware('auth')->group(function () {
    // Arahkan ke halaman admin setelah login
    Route::get('/dashboard', function () {
        return redirect()->route('admin.products.index');
    })->name('dashboard');

    // Rute untuk manajemen profil pengguna (bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- GRUP RUTE UNTUK ADMIN (DIAMANKAN DENGAN LOGIN) ---
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // Rute untuk Manajemen Produk (CRUD)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Rute untuk Riwayat Transaksi
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
});


// Memuat rute-rute otentikasi (login, register, dll) bawaan Breeze
require __DIR__.'/auth.php';
