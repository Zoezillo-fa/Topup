@extends('layouts.guest')

@section('title', 'Order ' . $game->name)

@section('content')
    <div x-data="{
        selectedProductId: null,
        selectedProductPrice: 0,
        selectedProductName: '',
        selectedChannel: null,
        selectedChannelName: '',
        userId: '',
        zoneId: ''
    }">

        {{-- Header Informasi Game --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md mb-8 flex items-start gap-6">
            <img src="{{ $game->thumbnail ?? 'https://via.placeholder.com/150x200/1e293b/ffffff?text=' . urlencode($game->name) }}" 
                 alt="{{ $game->name }}" 
                 class="w-24 h-32 md:w-32 md:h-44 object-cover rounded-lg shadow-md shrink-0">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $game->name }}</h2>
                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400 space-y-2 prose prose-sm dark:prose-invert max-w-none">
                    {!! $game->description !!}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- KOLOM KIRI (Form Input) --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- LANGKAH 1: Masukkan User ID --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
                    <div class="flex items-center border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                        <span class="bg-blue-600 text-white rounded-full h-8 w-8 text-lg flex items-center justify-center mr-3 font-bold">1</span>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">Lengkapi Data</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">User ID</label>
                            <input type="text" id="user_id" name="user_id_display" x-model="userId" placeholder="Masukkan User ID" required class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="zone_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Zone ID (Opsional)</label>
                            <input type="text" id="zone_id" name="zone_id_display" x-model="zoneId" placeholder="Jika ada" class="mt-1 block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                {{-- LANGKAH 2: Pilih Nominal Top Up --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
                    <div class="flex items-center border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                        <span class="bg-blue-600 text-white rounded-full h-8 w-8 text-lg flex items-center justify-center mr-3 font-bold">2</span>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">Pilih Nominal</h3>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($game->products as $product)
                            <div @click="selectedProductId = {{ $product->id }}; selectedProductPrice = {{ $product->price }}; selectedProductName = '{{ $product->denomination }}'"
                                 :class="{ 
                                    'border-blue-500 ring-2 ring-blue-500': selectedProductId == {{ $product->id }},
                                    'border-gray-300 dark:border-gray-600': selectedProductId != {{ $product->id }}
                                 }"
                                 class="cursor-pointer border-2 rounded-lg p-4 text-center hover:border-blue-400 transition-all duration-200">
                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $product->denomination }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Rp {{ number_format($product->price) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- LANGKAH 3: Pilih Metode Pembayaran --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
                    <div class="flex items-center border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                        <span class="bg-blue-600 text-white rounded-full h-8 w-8 text-lg flex items-center justify-center mr-3 font-bold">3</span>
                         <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">Pilih Pembayaran</h3>
                    </div>
                    <div class="space-y-4">
                         @if($channels)
                            @foreach ($channels as $channel)
                                @if($channel['active'])
                                    <div @click="selectedChannel = '{{ $channel['code'] }}'; selectedChannelName = '{{ $channel['name'] }}'"
                                         :class="{ 
                                            'border-blue-500 ring-2 ring-blue-500': selectedChannel == '{{ $channel['code'] }}',
                                            'border-gray-300 dark:border-gray-600': selectedChannel != '{{ $channel['code'] }}'
                                         }"
                                         class="cursor-pointer border-2 rounded-lg p-4 hover:border-blue-400 transition-all duration-200 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <img src="{{ $channel['icon_url'] }}" alt="{{ $channel['name'] }}" class="w-16 h-auto mr-4">
                                            <span class="font-semibold text-sm text-gray-800 dark:text-gray-200">{{ $channel['name'] }}</span>
                                        </div>
                                        <p class="font-bold text-gray-800 dark:text-gray-200" x-text="new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(selectedProductPrice)"></p>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN (Ringkasan Order & Tombol Beli) - UNTUK DESKTOP --}}
            <div class="lg:col-span-1 hidden lg:block">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md sticky top-28">
                    <h2 class="text-xl font-bold mb-4 border-b border-gray-200 dark:border-gray-700 pb-2 text-gray-800 dark:text-gray-100">Detail Pesanan</h2>
                    <form action="{{ route('order.store') }}" method="POST">
                        @csrf
                        {{-- Input tersembunyi untuk dikirim ke backend --}}
                        <input type="hidden" name="user_id" :value="userId + (zoneId ? '(' + zoneId + ')' : '')">
                        <input type="hidden" name="product_id" :value="selectedProductId">
                        <input type="hidden" name="channel" :value="selectedChannel">

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">User ID:</span>
                                <span class="font-semibold text-gray-800 dark:text-gray-200" x-text="userId + (zoneId ? '(' + zoneId + ')' : '')">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Produk:</span>
                                <span class="font-semibold text-gray-800 dark:text-gray-200" x-text="selectedProductName">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Metode Bayar:</span>
                                <span class="font-semibold text-gray-800 dark:text-gray-200" x-text="selectedChannelName">-</span>
                            </div>
                            <div class="border-t border-gray-200 dark:border-gray-700 my-3"></div>
                            <div class="flex justify-between text-lg">
                                <span class="text-gray-800 dark:text-gray-200 font-bold">Total Bayar:</span>
                                <span class="font-bold text-blue-600 dark:text-blue-400" x-text="new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(selectedProductPrice)">Rp 0</span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                    :disabled="!selectedProductId || !selectedChannel || !userId"
                                    class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg text-lg transition-all duration-300
                                           disabled:bg-gray-500 disabled:cursor-not-allowed
                                           hover:bg-blue-700">
                                Beli Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Ringkasan Order & Tombol Beli (UNTUK MOBILE) --}}
        <div class="lg:hidden fixed bottom-0 left-0 w-full bg-white dark:bg-gray-800 shadow-[0_-2px_10px_rgba(0,0,0,0.1)] p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Bayar:</p>
                    <p class="font-bold text-lg text-blue-600 dark:text-blue-400" x-text="new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(selectedProductPrice)">Rp 0</p>
                </div>
                {{-- Form ini diperlukan agar tombol Beli di mobile tetap berfungsi --}}
                <form action="{{ route('order.store') }}" method="POST" class="w-1/2">
                    @csrf
                    <input type="hidden" name="user_id" :value="userId + (zoneId ? '(' + zoneId + ')' : '')">
                    <input type="hidden" name="product_id" :value="selectedProductId">
                    <input type="hidden" name="channel" :value="selectedChannel">
                    <button type="submit"
                            :disabled="!selectedProductId || !selectedChannel || !userId"
                            class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg text-md transition-all duration-300
                                   disabled:bg-gray-500 disabled:cursor-not-allowed
                                   hover:bg-blue-700">
                        Beli Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection