<div class="bg-[#FAF8F5]">
    @php
        $categoryImage = function ($name) {
            $n = strtolower($name);
            return match (true) {
                str_contains($n, 'living') => 'assets/image/living-room.jpg',
                str_contains($n, 'bedroom') => 'assets/image/bedroom.jpg',
                str_contains($n, 'dining') => 'assets/image/dinning-table.jpeg',
                str_contains($n, 'kitchen') => 'assets/image/kitchen.jpg',
                default => 'assets/image/teakroot.jpg',
            };
        };
    @endphp

    {{-- HERO --}}
    <section class="relative min-h-[460px] lg:min-h-[540px] flex items-center overflow-hidden bg-charcoal">
        <img src="{{ asset('assets/image/hero-crop.jpg') }}" alt="" fetchpriority="high"
            class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-black/10"></div>

        <div class="relative z-10 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-16">
            <p class="inline-block border border-gold/60 text-gold text-[11px] font-semibold uppercase tracking-wider rounded-full px-4 py-1.5 mb-6">
                Certified legal teak from Lombok, Indonesia
            </p>
            <h1 class="font-serif font-bold text-white text-4xl md:text-5xl lg:text-6xl leading-tight max-w-xl">
                Timeless Elegance For Your Living Space
            </h1>
            <p class="text-stone-200 mt-5 max-w-lg leading-relaxed">
                Handcrafted from legally certified Perhutani solid teak by master Lombok woodcarvers.
                Generational tropical hardwood durability, with clean contemporary minimalism.
            </p>

            <div class="flex flex-wrap gap-3 mt-8">
                <a href="/products"
                    class="px-7 py-3 bg-accent hover:bg-accent-dark text-white font-semibold rounded-md transition">
                    Explore Collection &rsaquo;
                </a>
                <a href="/about"
                    class="px-7 py-3 border border-white/50 text-white font-semibold rounded-md hover:bg-white/10 transition">
                    Wood Provenance &amp; SVLK
                </a>
            </div>

            <div class="flex flex-wrap gap-x-10 gap-y-4 mt-12">
                <div><p class="font-serif font-bold text-gold text-2xl">100%</p><p class="text-xs text-stone-300">Certified legal teak</p></div>
                <div><p class="font-serif font-bold text-gold text-2xl">0 IDR</p><p class="text-xs text-stone-300">Free Lombok delivery</p></div>
                <div><p class="font-serif font-bold text-gold text-2xl">ISPM 15</p><p class="text-xs text-stone-300">Export-ready crating</p></div>
            </div>
        </div>
    </section>

    {{-- CATEGORIES --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-end justify-between mb-8 gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-accent mb-1">Best Collection</p>
                <h2 class="font-serif font-bold text-gray-900 text-3xl">Explore By Category</h2>
            </div>
            <a href="/products" class="text-sm font-semibold text-accent hover:underline whitespace-nowrap">View All Categories &raquo;</a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach ($categories as $category)
                <a href="/products?category={{ $category->slug }}" wire:key="cat-{{ $category->id }}"
                    class="group relative block aspect-[4/5] rounded-2xl overflow-hidden bg-charcoal shadow-md">
                    <img src="{{ asset($categoryImage($category->name)) }}" alt="" loading="lazy"
                        class="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 flex items-end justify-between gap-2">
                        <div>
                            <p class="text-[11px] font-semibold text-gold">{{ $category->products_count }} Products</p>
                            <h3 class="font-serif font-bold text-white text-lg leading-tight">{{ $category->name }}</h3>
                        </div>
                        <span class="shrink-0 w-8 h-8 rounded-full bg-white/20 text-white flex items-center justify-center group-hover:bg-accent transition">&rsaquo;</span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- BEST PRODUCTS --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="text-center mb-10">
            <p class="text-xs font-semibold uppercase tracking-wider text-accent mb-1">Featured Collection</p>
            <h2 class="font-serif font-bold text-gray-900 text-3xl">Our Best Quality Products</h2>
            <p class="text-gray-500 mt-3 max-w-xl mx-auto text-sm">
                Handcrafted solid teak pieces with organic sourcing and lifetime durability.
            </p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach ($products as $product)
                @php
                    $image = $product->galleries->first();
                    [$badgeText, $badgeColor] = match ($product->availability) {
                        'ready' => ['Ready Stock', 'bg-emerald-600'],
                        'pre_order' => ['Pre-Order', 'bg-accent'],
                        default => ['Sold Out', 'bg-gray-500'],
                    };
                    $rating = (int) round($product->reviews_avg_rating ?? 0);
                    $soldOut = $product->availability === 'out_of_stock';
                @endphp
                <div wire:key="prod-{{ $product->id }}"
                    class="group bg-white rounded-xl border border-stone-200 overflow-hidden flex flex-col hover:shadow-lg transition">
                    <a href="/products/{{ $product->slug }}" class="relative block aspect-[4/3] bg-stone-100">
                        @if ($image)
                            <img src="{{ asset('storage/' . $image->image_url) }}" alt="{{ $product->name }}" loading="lazy"
                                class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                        @else
                            <span class="absolute inset-0 flex items-center justify-center text-sm text-gray-400">No image</span>
                        @endif
                        <span class="absolute top-3 left-3 {{ $badgeColor }} text-white text-[10px] font-semibold rounded px-2 py-1">{{ $badgeText }}</span>
                    </a>

                    <div class="p-4 flex flex-col flex-1">
                        <div class="text-xs mb-1">
                            @if ($product->reviews_count > 0)
                                <span class="text-gold">{{ str_repeat('★', $rating) }}{{ str_repeat('☆', 5 - $rating) }}</span>
                                <span class="text-gray-400">({{ $product->reviews_count }})</span>
                            @else
                                <span class="text-gray-400">No reviews yet</span>
                            @endif
                        </div>
                        <h3 class="font-serif font-bold text-gray-900 truncate">
                            <a href="/products/{{ $product->slug }}" class="hover:text-accent">{{ $product->name }}</a>
                        </h3>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ \Illuminate\Support\Str::limit(strip_tags($product->description), 70) }}</p>

                        <div class="flex items-center justify-between mt-auto pt-4">
                            <p class="font-bold text-gray-900 text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <button type="button" wire:click="addToCart({{ $product->id }})" @disabled($soldOut)
                                class="text-xs font-semibold border border-gray-300 rounded-md px-3 py-1.5 hover:bg-brand hover:text-white hover:border-brand transition disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-transparent disabled:hover:text-inherit">
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-10">
            <a href="/products" class="inline-block bg-charcoal hover:bg-black text-white text-sm font-semibold rounded-md px-7 py-3 transition">
                Open Complete Catalog
            </a>
        </div>
    </section>

    {{-- WORKSHOP & CARGO --}}
    <section class="border-t border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-accent mb-2">Craftsmanship &amp; International Cargo</p>
                <h2 class="font-serif font-bold text-gray-900 text-3xl md:text-4xl leading-tight">
                    From Meninting Workshop to Prestigious Global Interiors
                </h2>
                <p class="text-gray-600 mt-4 text-sm leading-relaxed">
                    Rizqi Wood Gallery has manufactured and distributed hundreds of bespoke teak furniture sets for
                    private villas in Lombok, luxury resorts in Bali, and 40ft export containers for the USA,
                    Australia, and Europe.
                </p>

                <ul class="mt-6 space-y-4 text-sm text-gray-600">
                    @foreach ([
                        ['SVLK Certified Legal Wood:', 'Sourced from state-managed Perhutani forests, kiln-dried to a moisture level under 12%.'],
                        ['ISPM 15 Export Pallets:', 'Double-frame support and certified fumigation for safe ocean and air freight.'],
                        ['Free Local Island Delivery:', 'Our own fleet delivers to villas and resorts across Mataram & West Lombok.'],
                    ] as [$strong, $text])
                        <li class="flex gap-3">
                            <span class="mt-0.5 shrink-0 w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold flex items-center justify-center">&#10003;</span>
                            <span><strong class="text-gray-900">{{ $strong }}</strong> {{ $text }}</span>
                        </li>
                    @endforeach
                </ul>

                <a href="https://wa.me/6281945591108" target="_blank" rel="noopener"
                    class="inline-block mt-8 bg-accent hover:bg-accent-dark text-white text-sm font-semibold rounded-md px-6 py-3 transition">
                    Consult Custom Order via WhatsApp &rsaquo;
                </a>
            </div>

            <div class="grid grid-cols-2 gap-3 md:gap-4">
                <img src="{{ asset('assets/image/export-loading.jpeg') }}" alt="Loading furniture into a truck" loading="lazy" class="w-full aspect-square object-cover rounded-xl">
                <img src="{{ asset('assets/image/export-behind-truck.jpeg') }}" alt="Furniture packed for export" loading="lazy" class="w-full aspect-square object-cover rounded-xl mt-6">
                <img src="{{ asset('assets/image/export-side-truck.jpeg') }}" alt="Export container truck" loading="lazy" class="w-full aspect-square object-cover rounded-xl -mt-6">
                <img src="{{ asset('assets/image/dinning-table-modern.jpeg') }}" alt="Finished teak dining table" loading="lazy" class="w-full aspect-square object-cover rounded-xl">
            </div>
        </div>
    </section>

    {{-- REVIEWS --}}
    <section class="border-t border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="flex items-end justify-between gap-4 flex-wrap mb-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-accent mb-1">Client Testimonials</p>
                    <h2 class="font-serif font-bold text-gray-900 text-3xl">What Our Clients Say</h2>
                    <p class="text-gray-500 text-sm mt-2">Reviews from homeowners, villa managers, and architectural partners.</p>
                </div>
                <a href="/reviews" class="text-sm font-semibold text-accent hover:underline whitespace-nowrap">View All Reviews &amp; Statistics &raquo;</a>
            </div>

            @if ($reviews->isEmpty())
                <p class="text-center text-gray-500 py-10">Belum ada review dari pelanggan.</p>
            @else
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($reviews as $review)
                        <div wire:key="rev-{{ $review->id }}" class="bg-white border border-stone-200 rounded-xl p-5 flex flex-col">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gold">{{ str_repeat('★', (int) $review->rating) }}{{ str_repeat('☆', 5 - (int) $review->rating) }}</span>
                                <span class="text-gray-400">{{ $review->created_at->format('d M Y') }}</span>
                            </div>
                            <p class="italic text-gray-600 text-sm leading-relaxed mt-3 flex-1">
                                "{{ \Illuminate\Support\Str::limit($review->comment, 140) }}"
                            </p>
                            <div class="flex items-center gap-3 mt-4 pt-4 border-t border-stone-100">
                                <span class="w-8 h-8 rounded-full bg-amber-100 text-amber-800 text-xs font-bold flex items-center justify-center">
                                    {{ strtoupper(mb_substr($review->user->name, 0, 1)) }}
                                </span>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $review->user->name }}</p>
                                    <p class="text-[11px] text-gray-400">Verified buyer</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- CTA --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="rounded-2xl bg-gradient-to-br from-[#2c2019] to-charcoal p-8 md:p-12">
            <p class="text-xs font-semibold uppercase tracking-wider text-gold mb-2">Architects, Villas &amp; B2B Projects</p>
            <h2 class="font-serif font-bold text-white text-3xl md:text-4xl leading-tight max-w-2xl">
                Need Custom Dimensions for Your Architectural Plans?
            </h2>
            <p class="text-stone-300 text-sm leading-relaxed mt-4 max-w-2xl">
                Send us your blueprints, AutoCAD sketches, or 3D renders. We build bespoke pieces and complete
                furnishing packages for villas and resorts, with direct workshop pricing.
            </p>
            <div class="flex flex-wrap gap-3 mt-8">
                <a href="https://wa.me/6281945591108" target="_blank" rel="noopener"
                    class="bg-accent hover:bg-accent-dark text-white text-sm font-semibold rounded-md px-6 py-3 transition">
                    Chat WhatsApp (+62 819-4559-1108)
                </a>
                <a href="/contact"
                    class="border border-white/40 text-white text-sm font-semibold rounded-md px-6 py-3 hover:bg-white/10 transition">
                    Submit Project Inquiry
                </a>
            </div>
        </div>
    </section>
</div>