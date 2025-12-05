<div class="bg-white font-sans text-gray-700" x-data="{ mobileFiltersOpen: false }">

    <div class="relative h-64 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=2000&auto=format&fit=crop');">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-center items-center text-center">
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-3 tracking-wide">Our Collection</h1>
            <nav class="flex justify-center text-sm text-white/90 gap-2 items-center">
                <a href="/" class="hover:text-white transition">Home</a>
                <x-heroicon-m-chevron-right class="w-3 h-3 text-white/70" />
                <span class="font-medium text-white">Catalog</span>
            </nav>
        </div>
    </div>

    <div class="bg-white border-b border-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto relative">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search Product..."
                    class="w-full pl-6 pr-32 py-4 border border-gray-200 rounded-2xl shadow-sm focus:ring-2 focus:ring-amber-800 focus:border-transparent text-base outline-none transition placeholder-gray-400">
                <button class="absolute right-2 top-1/2 -translate-y-1/2 bg-[#6B4226] text-white px-12 py-2.5 rounded-xl hover:bg-[#5D3A20] transition font-medium shadow-md">
                    Search
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 border-b border-gray-100 pb-6">
            <p class="text-sm text-gray-500">
                Showing <span class="font-bold text-gray-900">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</span> of <span class="font-bold text-gray-900">{{ $products->total() }}</span> Products
            </p>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <span class="text-sm text-gray-500 whitespace-nowrap">Sort by:</span>
                <div class="relative w-full sm:w-48">
                    <select wire:model.live="sort" class="w-full appearance-none border-gray-200 text-sm rounded-lg focus:ring-amber-800 focus:border-amber-800 py-2.5 pl-4 pr-10 cursor-pointer bg-white outline-none shadow-sm">
                        <option value="latest">Most Popular</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-10">

            <div class="lg:w-64 flex-shrink-0">
                
                <button @click="mobileFiltersOpen = !mobileFiltersOpen"
                    class="lg:hidden w-full flex items-center justify-between bg-white border border-gray-300 py-3 px-4 rounded-lg text-gray-900 font-medium hover:bg-gray-50 transition mb-6 shadow-sm">
                    <span class="flex items-center gap-2">
                        <x-heroicon-o-funnel class="w-5 h-5" /> Filters
                    </span>
                    <svg class="w-5 h-5 transition-transform" :class="{'rotate-180': mobileFiltersOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <div :class="{ 'hidden': !mobileFiltersOpen, 'block': mobileFiltersOpen }"
                    class="hidden lg:block bg-white lg:bg-transparent border border-gray-200 lg:border-0 rounded-xl p-5 lg:p-0 space-y-8">

                    <div class="hidden lg:flex items-center justify-between mb-6">
                        <h3 class="font-bold text-gray-900 text-lg">Filters</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                    </div>

                    <div class="border-t border-gray-100 pt-6 lg:border-t-0 lg:pt-0">
                        <h4 class="font-bold text-gray-900 mb-4 text-sm uppercase tracking-wider">Categories</h4>
                        <div class="space-y-3">
                            @foreach ($categories as $category)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" wire:model.live="selectedCategories" value="{{ $category->id }}"
                                            class="peer h-5 w-5 cursor-pointer appearance-none rounded border border-gray-300 transition-all checked:border-amber-800 checked:bg-amber-800 hover:border-amber-600">
                                        <svg class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    </div>
                                    <span class="text-sm text-gray-600 group-hover:text-amber-800 transition font-medium">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="font-bold text-gray-900 mb-4 text-sm uppercase tracking-wider">Price Range</h4>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="relative w-full">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                <input wire:model.live.debounce.500ms="priceMin" type="number" placeholder="Min" class="w-full pl-8 pr-2 py-2 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-amber-800 focus:border-amber-800 outline-none">
                            </div>
                            <span class="text-gray-400">-</span>
                            <div class="relative w-full">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                <input wire:model.live.debounce.500ms="priceMax" type="number" placeholder="Max" class="w-full pl-8 pr-2 py-2 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-amber-800 focus:border-amber-800 outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="font-bold text-gray-900 mb-4 text-sm uppercase tracking-wider">Availability</h4>
                        <label class="flex items-center gap-3 cursor-pointer mb-2 group">
                            <div class="relative flex items-center">
                                <input wire:model.live="readyStock" type="checkbox" class="peer h-5 w-5 cursor-pointer appearance-none rounded border border-gray-300 transition-all checked:border-amber-800 checked:bg-amber-800 hover:border-amber-600">
                                <svg class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </div>
                            <span class="text-sm text-gray-600 group-hover:text-amber-800 transition font-medium">Ready Stock Only</span>
                        </label>
                    </div>

                    <button wire:click="resetFilters" class="w-full bg-gray-100 text-gray-600 py-2.5 rounded-lg font-semibold text-sm hover:bg-gray-200 transition mt-4">
                        Reset All Filters
                    </button>
                </div>
            </div>

            <div class="flex-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse ($products as $product)
                        <div class="group relative">
                            <div class="aspect-[4/5] w-full overflow-hidden rounded-xl bg-gray-100 relative mb-4">
                                @php $img = $product->galleries->first(); @endphp
                                @if ($img)
                                    <img src="{{ asset('storage/' . $img->image_url) }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center group-hover:scale-105 transition duration-500">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-gray-400 text-sm">No Image</div>
                                @endif

                                @if ($product->availability === 'pre_order')
                                    <span class="absolute top-3 left-3 bg-blue-600/90 backdrop-blur text-white text-[10px] font-bold px-2.5 py-1 rounded uppercase tracking-wider shadow-sm">Pre-Order</span>
                                @elseif($product->availability === 'out_of_stock' || $product->stock <= 0)
                                    <span class="absolute top-3 left-3 bg-gray-800/90 backdrop-blur text-white text-[10px] font-bold px-2.5 py-1 rounded uppercase tracking-wider shadow-sm">Sold Out</span>
                                @endif

                                <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-3 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-4 group-hover:translate-y-0 px-4">
                                    <button wire:click="addToCart({{ $product->id }})" class="flex-1 bg-white text-gray-900 py-2.5 rounded-lg font-bold text-xs uppercase tracking-wide hover:bg-[#6B4226] hover:text-white transition shadow-lg flex items-center justify-center gap-2">
                                        <x-heroicon-o-shopping-bag class="w-4 h-4" /> Add
                                    </button>
                                    <a href="/products/{{ $product->slug }}" class="w-10 h-10 bg-white text-gray-900 rounded-lg flex items-center justify-center hover:bg-gray-100 transition shadow-lg">
                                        <x-heroicon-o-eye class="w-5 h-5" />
                                    </a>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 mb-1">{{ $product->categories->first()->name ?? 'Furniture' }}</p>
                                <h3 class="text-base font-bold text-gray-900 mb-1">
                                    <a href="/products/{{ $product->slug }}">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="flex text-yellow-400 text-xs">
                                        <x-heroicon-s-star class="w-3.5 h-3.5" />
                                        <x-heroicon-s-star class="w-3.5 h-3.5" />
                                        <x-heroicon-s-star class="w-3.5 h-3.5" />
                                        <x-heroicon-s-star class="w-3.5 h-3.5" />
                                        <x-heroicon-s-star class="w-3.5 h-3.5 text-gray-200" />
                                    </div>
                                    <span class="text-xs text-gray-400">(4.5)</span>
                                </div>
                                <p class="text-lg font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-stone-50 mb-6">
                                <x-heroicon-o-face-frown class="w-10 h-10 text-gray-400" />
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">No products found</h3>
                            <p class="text-gray-500 mt-2 max-w-md mx-auto">We couldn't find what you're looking for. Try adjusting your filters.</p>
                            <button wire:click="resetFilters" class="mt-6 text-[#6B4226] font-bold hover:underline">Clear Filters</button>
                        </div>
                    @endforelse
                </div>

                <div class="mt-16">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>