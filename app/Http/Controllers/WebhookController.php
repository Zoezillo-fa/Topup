<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $privateKey = config('services.tripay.private_key');
        $signature = $request->header('X-Callback-Signature');
        $json = $request->getContent();
        $callbackSignature = hash_hmac('sha256', $json, $privateKey);

        if ($signature !== $callbackSignature) {
            Log::warning('Tripay Webhook: Signature tidak valid.', ['request' => $request->all()]);
            return response()->json(['success' => false, 'message' => 'Invalid Signature'], 403);
        }

        $data = json_decode($json);

        if ($data->status !== 'PAID') {
            return response()->json(['success' => true]);
        }

        $transaction = Transaction::where('merchant_ref', $data->merchant_ref)->first();

        if ($transaction && $transaction->status === 'UNPAID') {
            $transaction->update(['status' => 'PAID']);
            
            // Panggil method untuk proses top-up ke Digiflazz
            $this->processTopUp($transaction);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Mengirim permintaan top-up ke API Digiflazz.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    private function processTopUp(Transaction $transaction)
    {
        $username = config('services.digiflazz.username');
        $apiKey = config('services.digiflazz.api_key');
        $refId = $transaction->merchant_ref; // Gunakan referensi unik kita

        // Membuat signature MD5
        $signature = md5($username . $apiKey . $refId);

        // Ambil produk terkait untuk mendapatkan SKU supplier
        $product = $transaction->product;
        if (!$product || !$product->supplier_sku) {
            $transaction->update(['status' => 'FAILED']);
            Log::error('Digiflazz Top-up: Gagal, SKU supplier tidak ditemukan.', ['transaction_id' => $transaction->id]);
            return;
        }

        // Siapkan data untuk dikirim
        $data = [
            'username' => $username,
            'buyer_sku_code' => $product->supplier_sku,
            'customer_no' => $transaction->user_id_game,
            'ref_id' => $refId,
            'sign' => $signature,
        ];

        // Kirim request ke Digiflazz
        $response = Http::post(config('services.digiflazz.api_url') . 'transaction', $data);
        $result = $response->json('data');

        // Log respons dari Digiflazz untuk debugging
        Log::info('Digiflazz Top-up: Respons diterima.', ['response' => $result]);

        // Update status transaksi berdasarkan respons Digiflazz
        if (isset($result['status'])) {
            if ($result['status'] === 'Sukses') {
                $transaction->update(['status' => 'SUCCESS']);
            } elseif ($result['status'] === 'Gagal') {
                $transaction->update(['status' => 'FAILED']);
            }
            // Jika statusnya 'Pending', kita biarkan 'PAID' agar bisa dicek manual
        }
    }
}