<div class="bg-white font-sans text-gray-700">

    <div class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex text-sm text-gray-500 gap-2 items-center">
                <a href="/" class="hover:text-amber-800 transition">Home</a>
                <x-heroicon-m-chevron-right class="w-3 h-3 text-gray-400" />
                <a href="/products" class="hover:text-amber-800 transition">Catalog</a>
                <x-heroicon-m-chevron-right class="w-3 h-3 text-gray-400" />
                <span class="text-gray-900 font-medium truncate">{{ $product->name }}</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-16 lg:items-start">

            <div class="flex flex-col gap-4" x-data="{ activeImage: '{{ $product->galleries->firstWhere('is_thumbnail', true)?->image_url ? asset('storage/' . $product->galleries->firstWhere('is_thumbnail', true)->image_url) : ($product->galleries->first() ? asset('storage/' . $product->galleries->first()->image_url) : 'https://via.placeholder.com/500') }}' }">
                <div class="w-full aspect-square bg-gray-50 rounded-xl overflow-hidden relative border border-gray-100">
                    <img :src="activeImage" alt="{{ $product->name }}"
                        class="w-full h-full object-center object-contain p-4 hover:scale-105 transition duration-500">
                </div>
                <div class="grid grid-cols-5 gap-4">
                    @foreach ($product->galleries->take(5) as $gallery)
                        <button @click="activeImage = '{{ asset('storage/' . $gallery->image_url) }}'"
                            class="relative aspect-square bg-white rounded-lg flex items-center justify-center cursor-pointer border hover:border-amber-600 transition overflow-hidden focus:outline-none ring-offset-1 focus:ring-2 focus:ring-amber-500">
                            <img src="{{ asset('storage/' . $gallery->image_url) }}" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                <p class="text-sm text-gray-400 font-medium mb-1">
                    {{ $product->categories->first()->name ?? 'Furniture' }}</p>
                <h1 class="text-3xl md:text-4xl font-serif font-bold text-gray-900 tracking-tight mb-4 leading-tight">
                    {{ $product->name }}</h1>

                @php
                    $avgRating = $product->reviews()->where('is_approved', true)->avg('rating') ?? 0;
                    $reviewCount = $product->reviews()->where('is_approved', true)->count();
                @endphp
                <div class="flex items-center gap-4 text-sm mb-6">
                    <span class="text-gray-500"><strong class="text-gray-900">{{ $reviewCount * 5 }}+</strong> Already
                        Sold</span>
                    <div class="w-px h-4 bg-gray-300"></div>
                    <div class="flex items-center gap-1">
                        <span class="font-bold text-gray-900">{{ number_format($avgRating, 1) }}</span>
                        <x-heroicon-s-star class="w-4 h-4 text-yellow-400" />
                        <span class="text-gray-500">({{ $reviewCount }} Reviews)</span>
                    </div>
                </div>

                <div class="mb-4">
                    <span class="text-3xl font-bold text-gray-900">Rp
                        {{ number_format($product->price, 0, ',', '.') }}</span>
                    @if ($product->stock < 10 && $product->stock > 0)
                        <span class="text-sm text-gray-500 ml-2">Only <span
                                class="text-red-600 font-bold">{{ $product->stock }}</span> item(s) left!</span>
                    @endif
                </div>

                <p class="text-gray-600 text-sm leading-relaxed mb-6">Experience the warmth of solid teak wood combined
                    with ergonomic design. Handcrafted in Lombok to bring timeless elegance and comfort to your living
                    space.</p>

                <div class="mb-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-3">Quantity:</h3>
                    <div class="flex items-center border border-gray-300 rounded w-32">
                        <button wire:click="decrementQty"
                            class="px-3 py-2 text-gray-500 hover:bg-gray-100 w-10">-</button>
                        <input type="text" value="{{ $this->quantity }}"
                            class="w-12 text-center border-none p-0 text-gray-900 font-bold focus:ring-0" readonly>
                        <button wire:click="incrementQty"
                            class="px-3 py-2 text-gray-500 hover:bg-gray-100 w-10">+</button>
                    </div>
                </div>

                <div class="space-y-3 mb-8">
                    @if ($product->availability === 'ready')
                        <button wire:click="addToCart"
                            class="w-full bg-[#6B4226] text-white font-bold py-3.5 px-8 rounded hover:bg-[#5D3A20] transition flex items-center justify-center gap-2 shadow-sm">
                            <x-heroicon-o-shopping-cart class="w-5 h-5" /> Add To Cart
                        </button>
                    @else
                        <button disabled
                            class="w-full bg-gray-300 text-gray-500 font-bold py-3.5 px-8 rounded cursor-not-allowed">Stock
                            Not Available</button>
                    @endif
                    <a href="https://wa.me/6281945591108?text={{ urlencode('Hello Admin, I would like to inquire about a custom order for ' . $product->name . '. Could you please assist me?') }}"
                        target="_blank"
                        class="w-full border-2 border-[#6B4226] text-[#6B4226] font-bold py-3 px-8 rounded hover:bg-[#6B4226] hover:text-white transition flex items-center justify-center gap-2">
                        <x-heroicon-o-chat-bubble-left-ellipsis class="w-5 h-5" /> Request Custom Order
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-2xl font-serif font-bold text-gray-900 text-center mb-12">Product Detail</h2>

        <div class="grid md:grid-cols-[200px_1fr] gap-8 mb-16">
            <h3 class="font-serif font-bold text-xl text-gray-900">Description</h3>
            <div class="text-gray-600 text-sm leading-relaxed space-y-4">
                <div class="prose prose-sm max-w-none text-gray-600">{!! $product->description !!}</div>
                <div class="bg-gray-50 p-6 rounded-lg mt-6">
                    <h4 class="font-bold text-gray-900 mb-3 text-sm">Key Features:</h4>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-start gap-2"><x-heroicon-m-check
                                class="w-4 h-4 text-amber-700 mt-0.5" /><span>100% Solid Teak Wood (Legal Wood
                                Certified)</span></li>
                        <li class="flex items-start gap-2"><x-heroicon-m-check
                                class="w-4 h-4 text-amber-700 mt-0.5" /><span>Premium Matte Finishing (Water
                                Resistant)</span></li>
                        <li class="flex items-start gap-2"><x-heroicon-m-check
                                class="w-4 h-4 text-amber-700 mt-0.5" /><span>Handcrafted by local artisans in
                                Lombok</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-[200px_1fr] gap-8 mb-16">
            <h3 class="font-serif font-bold text-xl text-gray-900">Specification</h3>
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900 w-1/3 bg-gray-50">Material</td>
                            <td class="px-6 py-4 text-gray-500">{{ $product->material ?? 'Solid Teak Wood' }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900 bg-gray-50">Finishing</td>
                            <td class="px-6 py-4 text-gray-500">{{ $product->finishing ?? 'Natural' }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900 bg-gray-50">Dimensions</td>
                            <td class="px-6 py-4 text-gray-500">{{ $product->length_cm }} x {{ $product->width_cm }} x
                                {{ $product->height_cm }} cm</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <h3 class="font-serif font-bold text-2xl text-gray-900 text-center mb-10">Reviews</h3>

            <div class="bg-gray-50 rounded-2xl p-8 mb-8">
                <div class="flex flex-col md:flex-row items-center gap-10">
                    <div class="text-center md:text-left min-w-[150px]">
                        <span class="text-6xl font-bold text-gray-900">{{ number_format($avgRating, 1) }}</span>
                        <div class="flex text-yellow-400 justify-center md:justify-start gap-1 my-2">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($avgRating))
                                    <x-heroicon-s-star class="w-5 h-5" />
                                @else
                                    <x-heroicon-s-star class="w-5 h-5 text-gray-300" />
                                @endif
                            @endfor
                        </div>
                        <p class="text-sm text-gray-500">{{ $reviewCount }} Customer Reviews</p>
                    </div>

                    <div class="flex-1 w-full space-y-3">
                        @foreach ([5, 4, 3, 2, 1] as $star)
                            @php
                                $countStar = $product
                                    ->reviews()
                                    ->where('is_approved', true)
                                    ->where('rating', $star)
                                    ->count();
                                $percent = $reviewCount > 0 ? ($countStar / $reviewCount) * 100 : 0;
                            @endphp
                            <div class="flex items-center gap-4 text-xs">
                                <div class="flex items-center gap-1 w-12 text-gray-400">
                                    <x-heroicon-s-star class="w-4 h-4 text-yellow-400" /> {{ $star }}
                                </div>
                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-500 rounded-full" style="width: {{ $percent }}%">
                                    </div>
                                </div>
                                <span class="w-10 text-right text-gray-400">{{ round($percent) }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                @forelse ($product->reviews()->where('is_approved', true)->latest()->get() as $review)
                    <div class="flex gap-4 border-b border-gray-100 pb-8">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=random&color=fff"
                            class="w-12 h-12 rounded-full object-cover shadow-sm">

                        <div>
                            <h4 class="font-bold text-gray-900">
                                {{ $review->user->name }}
                                <span class="text-xs text-gray-400 font-normal ml-2">•
                                    {{ $review->created_at->diffForHumans() }}</span>
                            </h4>

                            <div class="flex text-yellow-400 text-xs my-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $review->rating)
                                        <x-heroicon-s-star class="w-4 h-4" />
                                    @else
                                        <x-heroicon-o-star class="w-4 h-4 text-gray-300" />
                                    @endif
                                @endfor
                            </div>

                            <p class="text-gray-600 text-sm mt-2 leading-relaxed">
                                {{ $review->comment }}
                            </p>

                            @if ($review->images && $review->images->count() > 0)
                                <div class="flex gap-2 mt-3">
                                    @foreach ($review->images as $img)
                                        <img src="{{ asset('storage/' . $img->image_url) }}"
                                            class="w-16 h-16 object-cover rounded-lg border border-gray-100 cursor-pointer hover:opacity-80">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500">No reviews yet for this product.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 border-t border-gray-200 mt-12">
        <h2 class="text-3xl font-serif font-bold text-gray-900 text-center mb-12">Related Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
            @foreach ($relatedProducts as $related)
                <a href="{{ route('products.show', $related->slug) }}" class="group cursor-pointer block">
                    <div class="relative bg-gray-100 rounded-2xl overflow-hidden aspect-[4/5] mb-4">
                        @php $img = $related->galleries->first(); @endphp
                        <img src="{{ $img ? asset('storage/' . $img->image_url) : 'https://via.placeholder.com/300' }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Furniture</p>
                        <h3 class="font-bold text-gray-900 text-lg group-hover:text-amber-800 transition">
                            {{ $related->name }}
                        </h3>
                        <div class="flex items-center gap-2 mt-1">
                            <x-heroicon-s-star class="w-4 h-4 text-yellow-400" />
                            <span class="text-sm font-bold text-gray-900">4.5</span>
                        </div>
                        <p class="font-bold text-gray-900 mt-2">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="text-center mt-12">
            <a href="/products"
                class="inline-block px-10 py-3 border-2 border-[#6B4226] text-[#6B4226] font-bold rounded hover:bg-[#6B4226] hover:text-white transition">View
                Collection</a>
        </div>
    </div>

</div>
