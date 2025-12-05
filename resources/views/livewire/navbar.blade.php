<nav x-data="{ mobileMenuOpen: false }" class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <a href="/" class="flex-shrink-0 flex items-center">
                <img src="{{ asset('assets/image/logo-big.png') }}" alt="Rizqi Wood Logo" class="h-9 w-auto">
            </a>

            <div class="hidden md:flex space-x-8">
                <a href="/"
                    class="text-gray-600 hover:text-amber-800 font-medium transition {{ request()->is('/') ? 'text-amber-800' : '' }}">
                    Home
                </a>
                <a href="/products"
                    class="text-gray-600 hover:text-amber-800 font-medium transition {{ request()->is('products*') ? 'text-amber-800' : '' }}">
                    Catalog
                </a>
                <a href="/reviews"
                    class="text-gray-600 hover:text-amber-800 font-medium transition {{ request()->is('reviews*') ? 'text-amber-800' : '' }}">
                    Reviews
                </a>
                <a href="/about"
                    class="text-gray-600 hover:text-amber-800 font-medium transition {{ request()->is('about*') ? 'text-amber-800' : '' }}">
                    About
                </a>
                <a href="/contact"
                    class="text-gray-600 hover:text-amber-800 font-medium transition {{ request()->is('contact*') ? 'text-amber-800' : '' }}">
                    Contact
                </a>
            </div>

            <div class="flex items-center space-x-6">

                <a href="/cart" class="relative group text-gray-500 hover:text-amber-800 transition"
                    aria-label="Shopping Cart">
                    <x-heroicon-o-shopping-bag class="w-6 h-6" />
                    @if (isset($total_count) && $total_count > 0)
                        <span
                            class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] font-bold rounded-full h-5 w-5 flex items-center justify-center shadow-sm">
                            {{ $total_count > 99 ? '99+' : $total_count }}
                        </span>
                    @endif
                </a>

                <div class="hidden md:block">
                    @auth
                        <div x-data="{ open: false }" class="relative">

                            <button @click="open = !open"
                                class="text-sm font-medium text-gray-600 hover:text-amber-800 flex items-center gap-1 focus:outline-none py-1">
                                {{ Str::limit(auth()->user()->name, 15) }}
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>

                            <div x-show="open" @click.outside="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-gray-100 z-50 origin-top-right"
                                style="display: none;">

                                <a href="/my-orders"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-amber-800 border-b border-gray-50">
                                    Riwayat Pesanan
                                </a>

                                <form method="POST" action="/logout">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-600">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="/login"
                            class="text-sm font-medium text-amber-800 hover:text-amber-900 border border-amber-800 px-4 py-1.5 rounded-full hover:bg-amber-50 transition">
                            Log in
                        </a>
                    @endauth
                </div>

                <div class="flex items-center md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button"
                        class="text-gray-600 hover:text-amber-800 focus:outline-none p-2">
                        <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <svg x-show="mobileMenuOpen" style="display: none;" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div x-show="mobileMenuOpen" x-transition
        class="md:hidden border-t border-gray-100 bg-white shadow-lg absolute w-full left-0 z-40"
        style="display: none;">
        <div class="px-4 pt-2 pb-4 space-y-1">
            <a href="/"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-amber-800 hover:bg-gray-50 {{ request()->is('/') ? 'bg-amber-50 text-amber-800' : '' }}">
                Home
            </a>
            <a href="/products"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-amber-800 hover:bg-gray-50 {{ request()->is('products*') ? 'bg-amber-50 text-amber-800' : '' }}">
                Catalog
            </a>

            <div class="border-t border-gray-100 my-2"></div>

            @auth
                <div class="px-3 py-2 text-sm font-bold text-gray-400 uppercase">
                    Account ({{ auth()->user()->name }})
                </div>
                <a href="/my-orders"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-amber-800 hover:bg-gray-50">
                    Order History
                </a>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit"
                        class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50">
                        Logout
                    </button>
                </form>
            @else
                <a href="/login" class="block px-3 py-2 rounded-md text-base font-bold text-amber-800 hover:bg-amber-50">
                    Log in / Register
                </a>
            @endauth
        </div>
    </div>
</nav>
