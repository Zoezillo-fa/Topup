{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ openSidebar: false }" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel') — {{ config('app.name', 'Zoestore Id') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-900 text-slate-100">
    <div class="min-h-screen flex">

        {{-- Sidebar (desktop) --}}
        <aside class="hidden md:flex md:flex-col md:w-64 bg-slate-950 border-r border-slate-800">
            <div class="h-16 flex items-center px-6 border-b border-slate-800">
                <a href="{{ url('/') }}" class="text-lg font-semibold tracking-tight">Zoestore Admin</a>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1">
                {{-- Produk --}}
                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                          {{ request()->routeIs('admin.products.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    {{-- (ikon opsional) --}}
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded bg-slate-800/60 border border-slate-700 text-[10px]">P</span>
                    Produk
                </a>

                {{-- Transaksi --}}
                <a href="{{ route('admin.transactions.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                          {{ request()->routeIs('admin.transactions.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    {{-- (ikon opsional) --}}
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded bg-slate-800/60 border border-slate-700 text-[10px]">T</span>
                    Transaksi
                </a>

                {{-- Tambah menu lain di sini --}}
                {{-- <a href="#" class="...">Pengaturan</a> --}}
            </nav>

            {{-- User / Logout --}}
            <div class="border-t border-slate-800 p-3">
                @auth
                    <div class="px-3 py-2 rounded-lg bg-slate-900 border border-slate-800 mb-2">
                        <div class="text-sm font-medium">{{ auth()->user()->name ?? 'Admin' }}</div>
                        <div class="text-xs text-slate-400">{{ auth()->user()->email ?? '' }}</div>
                    </div>
                    <div class="flex gap-2">
                        @if (Route::has('profile.edit'))
                            <a href="{{ route('profile.edit') }}" class="px-3 py-2 rounded-lg bg-slate-800 text-sm hover:bg-slate-700">Profil</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-lg bg-slate-800 text-sm hover:bg-slate-700">
                                Keluar
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </aside>

        {{-- Sidebar (mobile) --}}
        <div class="md:hidden">
            <div x-show="openSidebar" class="fixed inset-0 z-40 bg-black/50" @click="openSidebar=false"></div>
            <aside x-show="openSidebar" x-transition
                   class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-950 border-r border-slate-800">
                <div class="h-16 flex items-center px-6 border-b border-slate-800 justify-between">
                    <a href="{{ url('/') }}" class="text-lg font-semibold">Zoestore Admin</a>
                    <button class="p-2 rounded-md hover:bg-slate-800" @click="openSidebar=false">
                        ✕
                    </button>
                </div>
                <nav class="px-3 py-4 space-y-1">
                    <a href="{{ route('admin.products.index') }}"
                       class="block px-3 py-2 rounded-lg text-sm
                              {{ request()->routeIs('admin.products.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Produk
                    </a>
                    <a href="{{ route('admin.transactions.index') }}"
                       class="block px-3 py-2 rounded-lg text-sm
                              {{ request()->routeIs('admin.transactions.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Transaksi
                    </a>
                </nav>

                <div class="border-t border-slate-800 p-3">
                    @auth
                        <div class="px-3 py-2 rounded-lg bg-slate-900 border border-slate-800 mb-2">
                            <div class="text-sm font-medium">{{ auth()->user()->name ?? 'Admin' }}</div>
                            <div class="text-xs text-slate-400">{{ auth()->user()->email ?? '' }}</div>
                        </div>
                        <div class="flex gap-2">
                            @if (Route::has('profile.edit'))
                                <a href="{{ route('profile.edit') }}" class="px-3 py-2 rounded-lg bg-slate-800 text-sm hover:bg-slate-700">Profil</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-2 rounded-lg bg-slate-800 text-sm hover:bg-slate-700">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </aside>
        </div>

        {{-- Main area --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Topbar --}}
            <header class="h-16 bg-slate-900 border-b border-slate-800 flex items-center px-4 md:px-6">
                <button class="md:hidden mr-2 p-2 rounded-md hover:bg-slate-800" @click="openSidebar = true" aria-label="Open menu">
                    ☰
                </button>
                <div class="flex-1">
                    <h1 class="text-base sm:text-lg font-medium">@yield('page_title', 'Dashboard')</h1>
                </div>
                <div class="hidden sm:flex items-center gap-3">
                    <a href="{{ url('/') }}" class="text-sm text-slate-300 hover:text-white">Lihat Situs</a>
                </div>
            </header>

            {{-- Content --}}
            <main class="p-4 md:p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
