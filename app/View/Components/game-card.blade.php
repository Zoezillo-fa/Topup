{{-- Kita akan menggunakan variabel $game, bukan $product --}}
<a href="{{ route('order.create', $game) }}" class="group block rounded-xl overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 ease-in-out bg-white dark:bg-gray-800">
    <div class="relative">
        {{-- Gunakan gambar dari database, atau placeholder jika kosong --}}
        <img src="{{ $game->thumbnail ?? 'https://via.placeholder.com/300x400/1e293b/ffffff?text=' . urlencode($game->name) }}" 
             alt="{{ $game->name }}" 
             class="w-full h-48 object-cover">

        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        <div class="absolute bottom-0 left-0 p-4">
            <h3 class="font-bold text-white text-lg">{{ $game->name }}</h3>
        </div>
    </div>
</a>