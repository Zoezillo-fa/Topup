<a href="{{ route('order.create', $game) }}" class="group relative block rounded-xl overflow-hidden shadow-lg transform transition-all duration-300 ease-in-out hover:shadow-2xl hover:-translate-y-1">
    
    {{-- 1. Gambar Latar Belakang --}}
    <img src="{{ $game->thumbnail ?? 'https://via.placeholder.com/300x400/1e293b/ffffff?text=' . urlencode($game->name) }}" 
         alt="{{ $game->name }}" 
         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
    
    {{-- 2. Overlay Gradient Gelap --}}
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

    {{-- 3. Konten Teks di Atas Gambar --}}
    <div class="absolute inset-0 p-4 flex flex-col justify-end">
        {{-- Anda bisa menambahkan logo game di sini jika punya datanya --}}
        {{-- <img src="..." class="w-12 h-12 mb-2"> --}}
        
        <h3 class="font-bold text-white text-base leading-tight drop-shadow-md">{{ $game->name }}</h3>
        <p class="text-xs text-gray-300 drop-shadow-md mt-1">Top Up Instan</p>
    </div>

</a>