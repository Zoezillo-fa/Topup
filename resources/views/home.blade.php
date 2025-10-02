@extends('layouts.guest')

@section('title', 'Zoestore Id - Top Up Game Murah dan Cepat')

@section('content')
    
    {{-- Bagian Carousel/Slider Promosi --}}
    <div class="swiper mySwiper rounded-xl shadow-lg h-48 sm:h-64 mb-12">
        <div class="swiper-wrapper">
            <div class="swiper-slide bg-blue-600 text-white flex items-center justify-center">
                <div class="text-center p-4">
                    <h2 class="text-2xl sm:text-4xl font-bold">Selamat Datang di Zoestore Id!</h2>
                    <p class="mt-2 text-md sm:text-lg">Top Up Cepat, Murah, dan Terpercaya.</p>
                </div>
            </div>
            <div class="swiper-slide bg-purple-600 text-white flex items-center justify-center">
                 <div class="text-center p-4">
                    <h2 class="text-2xl sm:text-4xl font-bold">Promo Spesial Bulan Ini</h2>
                    <p class="mt-2 text-md sm:text-lg">Diskon hingga 50% untuk produk tertentu.</p>
                </div>
            </div>
        </div>
        <div class="swiper-button-next text-white"></div>
        <div class="swiper-button-prev text-white"></div>
        <div class="swiper-pagination"></div>
    </div>

    {{-- Wrapper untuk semua konten tab, diinisialisasi dengan Alpine.js --}}
    <div x-data="{ activeTab: '{{ $categories->first()->slug ?? '' }}' }">
        
        {{-- Bagian Tab Kategori Dinamis --}}
        <div class="mb-8">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-6 overflow-x-auto" aria-label="Tabs">
                    @foreach ($categories as $category)
                        <a href="#" @click.prevent="activeTab = '{{ $category->slug }}'"
                           :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': activeTab === '{{ $category->slug }}', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200': activeTab !== '{{ $category->slug }}' }"
                           class="shrink-0 whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium transition-colors duration-200">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>

        {{-- Konten Dinamis untuk Setiap Tab --}}
        @foreach ($categories as $category)
            <div x-show="activeTab === '{{ $category->slug }}'" style="display: none;">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
                    @forelse ($games->where('category_id', $category->id) as $game)
                        <x-game-card :game="$game" />
                    @empty
                        <p class="col-span-full text-center text-gray-500 dark:text-gray-400 py-8">
                            Tidak ada game dalam kategori ini.
                        </p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    {{-- Produk Digital Lainnya --}}
    <div class="mt-16">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">Produk Digital Lainnya</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="#" class="block bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md text-center hover:shadow-xl hover:-translate-y-1 transition-all duration-300"><div class="text-blue-500 mx-auto mb-2"><svg class="w-12 h-12 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" /></svg></div><h3 class="font-bold text-gray-800 dark:text-gray-200">Pulsa</h3></a>
            <a href="#" class="block bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md text-center hover:shadow-xl hover:-translate-y-1 transition-all duration-300"><div class="text-blue-500 mx-auto mb-2"><svg class="w-12 h-12 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.045A7.5 7.5 0 101.5 9.75a7.497 7.497 0 007.233 6.426l.24.961.328-1.348.328 1.348.24-.961a7.497 7.497 0 006.426-7.233 7.5 7.5 0 00-5.955-7.288" /></svg></div><h3 class="font-bold text-gray-800 dark:text-gray-200">Paket Data</h3></a>
            <a href="#" class="block bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md text-center hover:shadow-xl hover:-translate-y-1 transition-all duration-300"><div class="text-blue-500 mx-auto mb-2"><svg class="w-12 h-12 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 21z" /></svg></div><h3 class="font-bold text-gray-800 dark:text-gray-200">E-Money</h3></a>
            <a href="#" class="block bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md text-center hover:shadow-xl hover:-translate-y-1 transition-all duration-300"><div class="text-blue-500 mx-auto mb-2"><svg class="w-12 h-12 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-1.5h5.25m-5.25 0h5.25m-5.25 0h5.25m-5.25 0h5.25M3 4.5h15a2.25 2.25 0 012.25 2.25v10.5a2.25 2.25 0 01-2.25-2.25H3a2.25 2.25 0 01-2.25-2.25V6.75A2.25 2.25 0 013 4.5z" /></svg></div><h3 class="font-bold text-gray-800 dark:text-gray-200">Voucher</h3></a>
        </div>
    </div>

    {{-- Cara Top Up --}}
    <div class="mt-16">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100 text-center">Cara Top Up Cepat & Mudah</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md"><div class="text-blue-500 mx-auto mb-4 bg-blue-100 dark:bg-blue-900/50 rounded-full w-16 h-16 flex items-center justify-center"><span class="text-2xl font-bold">1</span></div><h3 class="font-bold text-lg text-gray-800 dark:text-gray-200">Pilih Produk</h3><p class="text-gray-600 dark:text-gray-400 mt-1 text-sm">Pilih game atau produk digital yang ingin Anda beli.</p></div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md"><div class="text-blue-500 mx-auto mb-4 bg-blue-100 dark:bg-blue-900/50 rounded-full w-16 h-16 flex items-center justify-center"><span class="text-2xl font-bold">2</span></div><h3 class="font-bold text-lg text-gray-800 dark:text-gray-200">Masukkan Data</h3><p class="text-gray-600 dark:text-gray-400 mt-1 text-sm">Masukkan User ID game Anda dan pilih nominal yang diinginkan.</p></div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md"><div class="text-blue-500 mx-auto mb-4 bg-blue-100 dark:bg-blue-900/50 rounded-full w-16 h-16 flex items-center justify-center"><span class="text-2xl font-bold">3</span></div><h3 class="font-bold text-lg text-gray-800 dark:text-gray-200">Lakukan Pembayaran</h3><p class="text-gray-600 dark:text-gray-400 mt-1 text-sm">Pilih metode pembayaran favoritmu dan selesaikan transaksi.</p></div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    var swiper = new Swiper(".mySwiper", {
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        loop: true,
    });
</script>
@endsectiona@extends('layouts.app')

@section('title', 'Zoestore Id — Top Up Game')

{{-- Halaman ini tetap tema default layout. Kalau ingin gelap:
@section('body_class', 'bg-slate-900 text-slate-100')
--}}

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">Pilih Game</h1>
        <p class="text-gray-600">Top up cepat dan aman untuk game favoritmu.</p>
    </div>

    {{-- Filter kategori (opsional) --}}
    @if(!empty($categories) && count($categories))
        <div class="flex flex-wrap gap-2 mb-6">
            @foreach($categories as $cat)
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm">
                    {{ $cat->name }}
                </span>
            @endforeach
        </div>
    @endif

    {{-- Grid Game --}}
    @if(!empty($games) && count($games))
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach ($games as $game)
                <div class="group rounded-2xl overflow-hidden bg-white shadow border border-gray-100 hover:shadow-md transition">
                    {{-- Thumbnail (opsional) --}}
                    <div class="aspect-video bg-gray-100 flex items-center justify-center text-gray-400">
                        {{-- Jika ada image: <img src="{{ $game->image_url }}" class="w-full h-full object-cover" alt="{{ $game->name }}"> --}}
                        <span class="text-sm">No Image</span>
                    </div>

                    <div class="p-4">
                        <h3 class="text-base font-semibold text-gray-900 truncate" title="{{ $game->name }}">
                            {{ $game->name }}
                        </h3>
                        @if($game->category)
                            <div class="text-xs text-gray-500 mt-0.5">{{ $game->category->name }}</div>
                        @endif>

                        <div class="mt-4 flex items-center justify-between">
                            {{-- INI BAGIAN PENTING: LINK KE /order/{slug} --}}
                            <a href="{{ route('order.create', $game) }}"
                               class="inline-flex items-center px-3 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-500">
                                Order
                            </a>

                            {{-- opsional: info produk minimal --}}
                            @php
                                $minPrice = optional($game->products)->min('price');
                            @endphp
                            @if($minPrice)
                                <div class="text-sm text-gray-600">Mulai <span class="font-semibold">Rp {{ number_format($minPrice,0,',','.') }}</span></div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-xl bg-yellow-50 border border-yellow-200 p-4 text-yellow-800">
            Belum ada game.
        </div>
    @endif
</div>
@endsection
