<nav x-data="{ open: false }" class="bg-slate-900 text-slate-100 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Left: Logo + Tabs --}}
            <div class="flex items-center gap-8">
                <a href="{{ url('/') }}" class="text-xl font-semibold tracking-tight">
                    Zoestore Id
                </a>

                {{-- Desktop tabs --}}
                <div class="hidden sm:flex items-center gap-6">
                    <a href="{{ url('/') }}"
                       class="inline-flex items-center text-sm font-medium pb-1 border-b-2
                              {{ request()->is('/') ? 'border-slate-100 text-white' : 'border-transparent text-slate-300 hover:text-white hover:border-slate-400/40' }}">
                        Beranda
                    </a>

                    <a href="{{ url('/order') }}"
                       class="inline-flex items-center text-sm font-medium pb-1 border-b-2
                              {{ request()->is('order*') ? 'border-slate-100 text-white' : 'border-transparent text-slate-300 hover:text-white hover:border-slate-400/40' }}">
                        Order
                    </a>

                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="inline-flex items-center text-sm font-medium pb-1 border-b-2
                                  {{ request()->is('dashboard') ? 'border-slate-100 text-white' : 'border-transparent text-slate-300 hover:text-white hover:border-slate-400/40' }}">
                            Dashboard
                        </a>

                        @if (Route::has('admin.products.index'))
                            <a href="{{ route('admin.products.index') }}"
                               class="inline-flex items-center text-sm font-medium pb-1 border-b-2
                                      {{ request()->is('admin/*') ? 'border-slate-100 text-white' : 'border-transparent text-slate-300 hover:text-white hover:border-slate-400/40' }}">
                                Admin
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Center: Search (desktop) --}}
            <div class="hidden md:flex flex-1 mx-6">
                <form action="{{ url('/order') }}" method="GET" class="w-full">
                    <label class="relative block">
                        <span class="sr-only">Cari game</span>
                        <input
                            name="q"
                            value="{{ request('q') }}"
                            class="w-full rounded-full bg-slate-800/60 border border-slate-700 px-4 py-2 pl-10 text-sm placeholder-slate-400 text-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-slate-500"
                            placeholder="Cari game favoritmu..."
                            type="search">
                        <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
                        </svg>
                    </label>
                </form>
            </div>

            {{-- Right: Auth / Account --}}
            <div class="hidden sm:flex items-center gap-3">
                @auth
                    <div class="relative" x-data="{ openUser:false }">
                        <button @click="openUser = !openUser"
                                class="px-3 py-2 rounded-lg bg-slate-800/60 border border-slate-700 hover:bg-slate-800 text-sm">
                            {{ auth()->user()->name ?? 'Akun' }}
                        </button>
                        <div x-show="openUser" @click.outside="openUser=false"
                             class="absolute right-0 mt-2 w-48 rounded-xl shadow-lg bg-white text-gray-700 ring-1 ring-black/5 z-50"
                             x-cloak>
                            <div class="py-1">
                                @if (Route::has('profile.edit'))
                                    <a href="{{ route('profile.edit') }}"
                                       class="block px-4 py-2 text-sm hover:bg-gray-100">Profil</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full text-left block px-4 py-2 text-sm hover:bg-gray-100">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth

                @guest
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}"
                           class="text-sm text-slate-300 hover:text-white">
                            Masuk
                        </a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center px-3 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-500">
                            Daftar
                        </a>
                    @endif
                @endguest

                {{-- Mobile toggler --}}
                <button @click="open = !open" class="sm:hidden inline-flex items-center justify-center p-2 rounded-md text-slate-300 hover:text-white hover:bg-slate-800 focus:outline-none">
                    <span class="sr-only">Open main menu</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile menu (dark) --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden border-t border-slate-800">
        <div class="px-4 py-3">
            <form action="{{ url('/order') }}" method="GET" class="mb-3">
                <label class="relative block">
                    <span class="sr-only">Cari game</span>
                    <input
                        name="q"
                        value="{{ request('q') }}"
                        class="w-full rounded-lg bg-slate-800/60 border border-slate-700 px-3 py-2 pl-9 text-sm placeholder-slate-400 text-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-slate-500"
                        placeholder="Cari game favoritmu..."
                        type="search">
                    <svg class="pointer-events-none absolute left-2.5 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                </label>
            </form>

            <a href="{{ url('/') }}"
               class="block px-3 py-2 rounded-md text-base font-medium
                      {{ request()->is('/') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                Beranda
            </a>
            <a href="{{ url('/order') }}"
               class="mt-1 block px-3 py-2 rounded-md text-base font-medium
                      {{ request()->is('order*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                Order
            </a>

            @auth
                <a href="{{ url('/dashboard') }}"
                   class="mt-1 block px-3 py-2 rounded-md text-base font-medium
                          {{ request()->is('dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    Dashboard
                </a>
                @if (Route::has('admin.products.index'))
                    <a href="{{ route('admin.products.index') }}"
                       class="mt-1 block px-3 py-2 rounded-md text-base font-medium
                              {{ request()->is('admin/*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Admin
                    </a>
                @endif
            @endauth

            @guest
                <div class="mt-3 flex items-center gap-3">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}"
                           class="text-sm text-slate-300 hover:text-white">Masuk</a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center px-3 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-500">
                            Daftar
                        </a>
                    @endif
                </div>
            @endguest
        </div>
    </div>
</nav>
