@extends('layouts.app')

@section('title', 'Instruksi Pembayaran')
{{-- Inilah kuncinya: pakai tema gelap untuk halaman ini --}}
@section('body_class', 'bg-slate-900 text-slate-100')

@section('content')
@php
    use Illuminate\Support\Carbon;

    // Normalisasi & fallback
    $reference  = $transaction->reference ?? $transaction->merchant_ref ?? '-';
    $channel    = $transaction->payment_channel ?? $transaction->payment_method ?? '-';
    $amount     = $transaction->amount ?? $transaction->total ?? $transaction->total_price ?? 0;
    $expiredAt  = $transaction->expired_time ?? $transaction->expired_at ?? $transaction->expired ?? (optional($transaction->created_at)->addMinutes(30) ?? now());
    $payUrl     = $transaction->pay_url ?? $transaction->checkout_url ?? $transaction->invoice_url ?? null;
    $qrUrl      = $transaction->qr_url ?? null;
    $qrString   = $transaction->qr_string ?? null;
    $payCode    = $transaction->pay_code ?? $transaction->va_number ?? null;

    $status     = strtoupper($transaction->status ?? 'UNPAID');
    $statusClass = match ($status) {
        'PAID', 'SUCCESS' => 'bg-green-500/15 text-green-300 ring-1 ring-inset ring-green-500/30',
        'EXPIRED', 'CANCEL' => 'bg-red-500/15 text-red-300 ring-1 ring-inset ring-red-500/30',
        default => 'bg-yellow-500/15 text-yellow-300 ring-1 ring-inset ring-yellow-500/30',
    };
@endphp

<div class="max-w-3xl mx-auto px-4 py-8">
    {{-- Kartu gelap seragam dengan halaman Order --}}
    <div class="rounded-2xl overflow-hidden bg-slate-900 border border-slate-800 shadow">
        {{-- Header gelap --}}
        <div class="px-6 py-4 bg-slate-900/80 border-b border-slate-800 flex items-start justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold text-white">Instruksi Pembayaran</h1>
                <p class="text-sm text-slate-400 mt-0.5">
                    Silakan selesaikan pembayaran Anda sesuai metode yang dipilih.
                </p>
            </div>
            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $statusClass }}">
                {{ $status }}
            </span>
        </div>

        {{-- Body --}}
        <div class="p-6">
            {{-- Detail transaksi --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 my-2">
                <div class="rounded-xl p-4 bg-slate-800 border border-slate-700">
                    <div class="text-xs text-slate-400">Kode Referensi</div>
                    <div class="font-mono text-lg break-all text-slate-100">{{ $reference }}</div>
                </div>
                <div class="rounded-xl p-4 bg-slate-800 border border-slate-700">
                    <div class="text-xs text-slate-400">Metode / Channel</div>
                    <div class="text-lg text-slate-100">{{ $channel }}</div>
                </div>
                <div class="rounded-xl p-4 bg-slate-800 border border-slate-700">
                    <div class="text-xs text-slate-400">Total</div>
                    <div class="text-lg font-semibold text-white">Rp {{ number_format($amount, 0, ',', '.') }}</div>
                </div>
                <div class="rounded-xl p-4 bg-slate-800 border border-slate-700">
                    <div class="text-xs text-slate-400">Batas Waktu</div>
                    <div class="text-lg text-slate-100">{{ Carbon::parse($expiredAt)->format('d M Y H:i') }} WIB</div>
                </div>
            </div>

            {{-- QR --}}
            <div class="mt-6">
                <h2 class="text-xl font-semibold mb-3 text-white">Kode QR</h2>
                <div class="rounded-xl p-4 bg-slate-800 border border-slate-700">
                    @if(!empty($qrUrl))
                        <img src="{{ $qrUrl }}" alt="QR Pembayaran"
                             class="mx-auto block w-60 h-60 object-contain rounded-lg ring-1 ring-slate-700" />
                    @elseif(!empty($qrString))
                        <div class="inline-block mx-auto bg-white p-2 rounded-lg">
                            {!! QrCode::size(240)->margin(1)->generate($qrString) !!}
                        </div>
                    @elseif(!empty($payUrl))
                        <div class="inline-block mx-auto bg-white p-2 rounded-lg">
                            {!! QrCode::size(240)->margin(1)->generate($payUrl) !!}
                        </div>
                    @else
                        <div class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-yellow-500/15 text-yellow-300 ring-1 ring-inset ring-yellow-500/30">
                            QR belum tersedia
                        </div>
                        <p class="text-sm text-slate-400 mt-2">Silakan muat ulang halaman ini atau coba metode lain.</p>
                    @endif
                </div>
            </div>

            {{-- Info tambahan --}}
            <div class="space-y-3 mt-6">
                @if(!empty($payUrl))
                    <div class="rounded-xl p-4 bg-slate-800 border border-slate-700">
                        <div class="text-xs text-slate-400 mb-1">Tautan Pembayaran</div>
                        <a href="{{ $payUrl }}" target="_blank" class="text-blue-400 hover:text-blue-300 break-all">
                            {{ $payUrl }}
                        </a>
                    </div>
                @endif

                @if(!empty($payCode))
                    <div class="rounded-xl p-4 bg-slate-800 border border-slate-700">
                        <div class="text-xs text-slate-400 mb-1">Kode/VA</div>
                        <div class="font-mono text-lg break-all text-slate-100">{{ $payCode }}</div>
                        <button type="button"
                                class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-700 text-slate-100 text-sm hover:bg-slate-600 mt-2"
                                onclick="navigator.clipboard.writeText(`{{ $payCode }}`); this.textContent='Tersalin!'; setTimeout(()=>this.textContent='Salin',1500);">
                            Salin
                        </button>
                    </div>
                @endif
            </div>

            <div class="mt-8 flex items-center justify-between">
                <a href="{{ url('/') }}" class="text-slate-300 hover:text-white">&larr; Kembali ke Beranda</a>
                @if(!empty($payUrl))
                    <a href="{{ $payUrl }}" target="_blank"
                       class="inline-flex items-center px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-500">
                        Buka Halaman Pembayaran
                    </a>
                @endif
            </div>
        </div>
    </div>

    <p class="text-xs text-slate-400 mt-4">
        Jika Anda sudah membayar, status akan diperbarui otomatis setelah kami menerima konfirmasi dari penyedia pembayaran.
    </p>
</div>
@endsection
