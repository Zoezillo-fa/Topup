<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Zoestore Id')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
</head>
<body class="bg-gray-100 dark:bg-gray-900 font-sans">

    {{-- HEADER / NAVBAR --}}
    <header class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                    Zoestore Id
                </a>

                <div class="hidden md:block w-1/3">
                    <div class="relative">
                        <input type="search" placeholder="Cari game favoritmu..." class="w-full bg-gray-100 dark:bg-gray-700 border-none rounded-full py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 absolute top-1/2 right-4 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"></path></svg>
                    </div>
                </div>

                <nav class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('admin.products.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-500">Admin Panel</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="text-gray-700 dark:text-gray-300 hover:text-blue-500">Log Out</a>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-500">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition-colors">Daftar</a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    {{-- KONTEN UTAMA --}}
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-white dark:bg-gray-800 border-t dark:border-gray-700 mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
                <div>
                    <h3 class="text-lg font-bold text-blue-600 dark:text-blue-400 mb-2">Zoestore Id</h3>
                    <p class="text-gray-600 dark:text-gray-400">Platform top up game termurah, tercepat, dan terpercaya di Indonesia.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-2">Link Cepat</h3>
                    <ul>
                        <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:underline">Cara Order</a></li>
                        <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:underline">Hubungi Kami</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-2">Pembayaran Aman</h3>
                    <p class="text-gray-600 dark:text-gray-400">Semua transaksi dijamin aman dengan enkripsi terbaik.</p>
                </div>
            </div>
            <div class="border-t dark:border-gray-700 mt-8 pt-4 text-center text-gray-500">
                <p>&copy; {{ date('Y') }} Zoestore Id. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    @yield('scripts')
</body>
</html>