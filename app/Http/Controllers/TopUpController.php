<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TopUpController extends Controller
{
    /**
     * Menampilkan halaman utama dengan daftar game dan kategori.
     */
    public function index()
    {
        $categories = Category::all();
        $games = Game::with('category')->get();

        // Ambil channel pembayaran dari Tripay (best-effort)
        $channels = [];
        try {
            $apiKey = config('services.tripay.api_key');
            if ($apiKey) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                ])->get(config('services.tripay.api_url') . 'merchant/payment-channel');

                if ($response->successful()) {
                    $channels = $response->json('data');
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengambil channel pembayaran Tripay: ' . $e->getMessage());
        }

        return view('home', [
            'categories' => $categories,
            'games'      => $games,
            'channels'   => $channels,
        ]);
    }

    /**
     * Menampilkan form order untuk game yang dipilih (binding via SLUG).
     * Route: GET /order/{game:slug}
     */
    public function showOrderForm(Game $game)
    {
        // Eager load products agar pilihan nominal siap
        $game->load('products');

        // Ambil channel pembayaran (best-effort)
        $channels = [];
        try {
            $apiKey = config('services.tripay.api_key');
            if ($apiKey) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                ])->get(config('services.tripay.api_url') . 'merchant/payment-channel');

                if ($response->successful()) {
                    $channels = $response->json('data');
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengambil channel pembayaran Tripay: ' . $e->getMessage());
        }

        return view('order-form', [
            'game'     => $game,
            'channels' => $channels,
        ]);
    }

    /**
     * Memproses pesanan, membuat transaksi di Tripay, dan menyimpannya di database.
     * Route: POST /order
     */
    public function order(Request $request)
    {
        $validated = $request->validate([
            'user_id'    => 'required|string',
            'product_id' => 'required|integer|exists:products,id',
            'channel'    => 'required|string',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $channelCode = $validated['channel'];
        $merchantRef = 'ZSI-' . time(); // Referensi unik kita

        // Data untuk Tripay
        $data = [
            'method'         => $channelCode,
            'merchant_ref'   => $merchantRef,
            'amount'         => $product->price,
            'customer_name'  => 'user-' . $validated['user_id'],
            'customer_email' => 'user-' . $validated['user_id'] . '@zoestore.id',
            'order_items'    => [[
                'name'     => $product->denomination,
                'price'    => $product->price,
                'quantity' => 1,
            ]],
            'expired_time'   => (time() + (24 * 60 * 60)), // 24 jam
        ];

        // Signature Tripay
        $privateKey   = config('services.tripay.private_key');
        $merchantCode = config('services.tripay.merchant_code');
        $signature    = hash_hmac('sha256', $merchantCode . $merchantRef . $product->price, $privateKey);
        $data['signature'] = $signature;

        // Call API Tripay
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.tripay.api_key'),
        ])->post(config('services.tripay.api_url') . 'transaction/create', $data);

        $result = $response->json('data');

        if ($response->json('success') !== true) {
            return back()->with('error', 'Gagal membuat transaksi, silakan coba lagi nanti.');
        }

        // Simpan transaksi lokal
        $transaction = Transaction::create([
            'product_id'     => $product->id,
            'user_id_game'   => $validated['user_id'],
            'status'         => 'UNPAID',
            'total_price'    => $result['amount'],
            'reference'      => $result['reference'],
            'merchant_ref'   => $merchantRef,
            'payment_method' => $result['payment_name'],
            'pay_code'       => $result['pay_code'] ?? null,
            'qr_url'         => $result['qr_string'] ?? null,
            // kalau Tripay mengembalikan checkout_url/pay_url kamu bisa simpan juga
            'pay_url'        => $result['checkout_url'] ?? ($result['pay_url'] ?? null),
        ]);

        return redirect()->route('order.success', ['transaction' => $transaction->id]);
    }

    /**
     * Menampilkan halaman instruksi pembayaran.
     */
    public function success(Transaction $transaction)
    {
        return view('order_success', ['transaction' => $transaction]);
    }
}
