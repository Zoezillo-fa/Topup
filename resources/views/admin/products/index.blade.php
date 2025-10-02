@extends('layouts.admin')

@section('title', 'Produk')
@section('page_title', 'Produk')

@section('content')
    {{-- Flash message --}}
    @if (session('status'))
        <div class="mb-4 rounded-lg border border-green-700 bg-green-900/30 text-green-200 px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-xl font-semibold">Daftar Produk</h2>
        <a href="{{ route('admin.products.create') }}"
           class="inline-flex items-center px-3 py-2 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-500">
            + Tambah Produk
        </a>
    </div>

    {{-- Tabel produk (contoh minimal) --}}
    <div class="rounded-xl border border-slate-800 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-800">
            <thead class="bg-slate-900/70">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300">Game</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300">Harga</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800 bg-slate-900/40">
                @forelse ($products as $product)
                    <tr>
                        <td class="px-4 py-3 text-sm text-slate-200">{{ $product->id }}</td>
                        <td class="px-4 py-3 text-sm text-slate-200">{{ optional($product->game)->name }}</td>
                        <td class="px-4 py-3 text-sm text-slate-200">{{ $product->denomination ?? $product->name }}</td>
                        <td class="px-4 py-3 text-sm text-slate-200">Rp {{ number_format($product->price,0,',','.') }}</td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="px-3 py-1.5 rounded-md bg-slate-800 hover:bg-slate-700">Edit</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                      onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-md bg-red-700/70 hover:bg-red-700">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-slate-400">Belum ada produk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
