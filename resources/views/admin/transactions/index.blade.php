@extends('layouts.admin')

@section('title', 'Transaksi')
@section('page_title', 'Transaksi')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-xl font-semibold">Riwayat Transaksi</h2>
        {{-- Filter/aksi opsional --}}
    </div>

    <div class="rounded-xl border border-slate-800 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-800">
            <thead class="bg-slate-900/70">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300">Produk</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300">User ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800 bg-slate-900/40">
                @forelse ($transactions as $tx)
                    <tr>
                        <td class="px-4 py-3 text-sm text-slate-200">{{ $tx->id }}</td>
                        <td class="px-4 py-3 text-sm text-slate-200">{{ optional($tx->product)->denomination ?? optional($tx->product)->name }}</td>
                        <td class="px-4 py-3 text-sm text-slate-200">{{ $tx->user_id_game }}</td>
                        <td class="px-4 py-3 text-sm">
                            @php $st = strtoupper($tx->status ?? 'UNPAID'); @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs
                                @if(in_array($st, ['PAID','SUCCESS'])) bg-green-500/15 text-green-300 ring-1 ring-green-500/30
                                @elseif(in_array($st, ['EXPIRED','CANCEL'])) bg-red-500/15 text-red-300 ring-1 ring-red-500/30
                                @else bg-yellow-500/15 text-yellow-300 ring-1 ring-yellow-500/30
                                @endif">
                                {{ $st }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-200">Rp {{ number_format($tx->total_price ?? 0,0,',','.') }}</td>
                        <td class="px-4 py-3 text-sm text-slate-400">{{ optional($tx->created_at)->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-slate-400">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
