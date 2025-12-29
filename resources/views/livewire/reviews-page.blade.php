<div class="bg-white font-sans text-gray-700 min-h-screen">

    <div class="bg-gray-50 border-b border-gray-100 py-16 text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-gray-900 mb-4">Loved by Locals, Trusted Globally
            </h1>
            <p class="text-gray-500 max-w-2xl mx-auto text-lg">
                See what our customers from Indonesia and around the world have to say about our craftsmanship and
                service.
            </p>

            <div class="mt-12 flex flex-wrap justify-center gap-6 md:gap-12">
                <div class="bg-white px-8 py-6 rounded-2xl shadow-sm border border-gray-200 w-48">
                    <span class="block text-4xl font-bold text-gray-900 mb-1">
                        {{ number_format($stats['avg_rating'], 1) }}
                    </span>
                    <span class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Average Rating</span>
                </div>
                <div class="bg-white px-8 py-6 rounded-2xl shadow-sm border border-gray-200 w-48">
                    <span class="block text-4xl font-bold text-gray-900 mb-1">
                        {{ $stats['total_reviews'] }}+
                    </span>
                    <span class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Total Reviews</span>
                </div>
                <div class="bg-white px-8 py-6 rounded-2xl shadow-sm border border-gray-200 w-48">
                    <span class="block text-4xl font-bold text-gray-900 mb-1">
                        {{ round($stats['recommendation']) }}%
                    </span>
                    <span class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Recommendation</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        <div class="flex flex-wrap justify-center gap-4 mb-12">
            <button wire:click="setFilter('all')"
                class="px-6 py-2.5 rounded-full font-medium border transition-all duration-300 {{ $filter === 'all' ? 'bg-[#6B4226] text-white border-[#6B4226]' : 'bg-white text-gray-600 border-gray-300 hover:border-[#6B4226] hover:text-[#6B4226]' }}">
                All Reviews
            </button>
            <button wire:click="setFilter('photos')"
                class="px-6 py-2.5 rounded-full font-medium border transition-all duration-300 {{ $filter === 'photos' ? 'bg-[#6B4226] text-white border-[#6B4226]' : 'bg-white text-gray-600 border-gray-300 hover:border-[#6B4226] hover:text-[#6B4226]' }}">
                With Photos
            </button>
            <button wire:click="setFilter('5stars')"
                class="px-6 py-2.5 rounded-full font-medium border transition-all duration-300 {{ $filter === '5stars' ? 'bg-[#6B4226] text-white border-[#6B4226]' : 'bg-white text-gray-600 border-gray-300 hover:border-[#6B4226] hover:text-[#6B4226]' }}">
                5 Stars Only
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @forelse($reviews as $review)
                <div
                    class="bg-gray-50 rounded-2xl p-6 border border-gray-100 hover:shadow-md transition duration-300 flex flex-col h-full">

                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=random&color=fff"
                                class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">{{ $review->user->name }}</h4>
                                <p class="text-xs text-gray-400">Verified Buyer</p>
                            </div>
                        </div>
                        <div class="flex text-yellow-400 text-xs">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                    <x-heroicon-s-star class="w-4 h-4" />
                                @else
                                    <x-heroicon-o-star class="w-4 h-4 text-gray-300" />
                                @endif
                            @endfor
                        </div>
                    </div>

                    @if ($review->images->count() > 0)
                        <div class="mb-4 flex gap-2 overflow-x-auto pb-2 scrollbar-hide snap-x">
                            @foreach ($review->images as $image)
                                <div
                                    class="flex-shrink-0 w-full rounded-xl overflow-hidden aspect-video relative group snap-center">
                                    <img src="{{ asset('storage/' . $image->image_url) }}"
                                        class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500"
                                        loading="lazy">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="flex-grow">
                        <h3 class="font-bold text-gray-800 mb-2">"{{ Str::limit($review->comment, 30, '...') }}"</h3>
                        <p class="text-sm text-gray-600 leading-relaxed mb-6">
                            {{ $review->comment }}
                        </p>
                    </div>

                    @if ($review->product)
                        <div class="mt-auto pt-4 border-t border-gray-200 flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-md bg-white border border-gray-200 overflow-hidden flex-shrink-0">
                                @php $img = $review->product->galleries->first(); @endphp
                                <img src="{{ $img ? asset('storage/' . $img->image_url) : 'https://via.placeholder.com/100' }}"
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs text-gray-500 mb-0.5">Purchased:</p>
                                <a href="/products/{{ $review->product->slug }}"
                                    class="text-xs font-bold text-gray-900 truncate block hover:text-amber-800 transition">
                                    {{ $review->product->name }}
                                </a>
                            </div>
                        </div>
                    @endif

                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <x-heroicon-o-chat-bubble-left-right class="w-8 h-8 text-gray-400" />
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">No reviews found</h3>
                    <p class="text-gray-500 mt-1">Try changing the filter.</p>
                </div>
            @endforelse

        </div>

        @if ($total_available > $total_visible)
            <div class="text-center mt-16">
                <button wire:click="loadMore" wire:loading.attr="disabled"
                    class="bg-white border border-gray-300 text-gray-700 font-bold py-3 px-8 rounded-full hover:bg-gray-50 hover:border-gray-400 transition shadow-sm flex items-center gap-2 mx-auto disabled:opacity-50">
                    <span wire:loading.remove>Load More Reviews</span>
                    <span wire:loading>Loading...</span>
                </button>
            </div>
        @endif

    </div>
</div>
