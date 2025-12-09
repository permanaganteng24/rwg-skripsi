<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto bg-gray-50 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="mb-8 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Shopping Cart</h1>
            <span class="text-gray-500 text-sm">{{ count($cart_items) }} Items</span>
        </div>

        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">

            {{-- KOLOM KIRI: ITEM LIST --}}
            <div class="flex-1 space-y-6">
                @forelse ($cart_items as $item)
                    <div
                        class="bg-white border border-gray-200 rounded-2xl p-4 sm:p-6 flex gap-6 items-center shadow-sm hover:shadow-md transition duration-300">
                        {{-- Image --}}
                        <div
                            class="w-24 h-24 sm:w-32 sm:h-32 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0 border border-gray-100">
                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}"
                                class="w-full h-full object-cover">
                        </div>

                        {{-- Details --}}
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $item['name'] }}</h3>
                            <p class="text-amber-700 font-semibold mb-4">Rp
                                {{ number_format($item['price'], 0, ',', '.') }}</p>

                            <div class="flex items-center gap-6">
                                {{-- Qty Control --}}
                                <div class="flex items-center border border-gray-300 rounded-lg bg-gray-50">
                                    <button wire:click="decrementQty({{ $item['product_id'] }})"
                                        class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-200 rounded-l-lg transition">-</button>
                                    <span
                                        class="w-10 text-center text-sm font-bold text-gray-900">{{ $item['quantity'] }}</span>
                                    <button wire:click="incrementQty({{ $item['product_id'] }})"
                                        class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-200 rounded-r-lg transition">+</button>
                                </div>

                                {{-- Remove --}}
                                <button wire:click="removeItem({{ $item['product_id'] }})"
                                    class="text-red-500 hover:text-red-700 text-sm font-medium flex items-center gap-1 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                    Remove
                                </button>
                            </div>
                        </div>

                        {{-- Total Per Item --}}
                        <div class="hidden sm:block text-right">
                            <p class="font-bold text-gray-900 text-lg">Rp
                                {{ number_format($item['total_amount'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-300">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-gray-900">Your cart is empty</h3>
                        <p class="text-gray-500 mb-6">Looks like you haven't added anything yet.</p>
                        <a href="/products"
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-bold rounded-full text-white bg-[#6B4226] hover:bg-[#5D3A20] transition">
                            Start Shopping
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- KOLOM KANAN: SUMMARY --}}
            <div class="w-full lg:w-96">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-xl shadow-gray-100 p-6 sticky top-24">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>

                    {{-- List Harga --}}
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-base text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-bold text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        {{-- Diskon --}}
                        @if ($discount > 0)
                            <div
                                class="flex justify-between items-center text-green-700 bg-green-50 px-3 py-2 rounded-lg border border-green-100">
                                <span class="flex items-center gap-2 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                        </path>
                                    </svg>
                                    <span class="font-bold uppercase">{{ $applied_coupon_code }}</span>
                                </span>
                                <span class="font-bold text-sm">- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-base text-gray-600">
                            <span>Shipping</span>
                            <span class="italic text-gray-400 text-sm">Calculated at checkout</span>
                        </div>
                    </div>

                    <hr class="border-gray-100 mb-6">

                    {{-- Total Besar --}}
                    <div class="flex justify-between items-center mb-8">
                        <span class="text-lg font-bold text-gray-900">Total</span>
                        <span class="text-2xl font-medium text-[#6B4226]">Rp
                            {{ number_format($grand_total, 0, ',', '.') }}</span>
                    </div>

                    {{-- BAGIAN KUPON --}}
                    <div class="mb-8">

                        {{-- Feedback Pesan --}}
                        @if (session()->has('success'))
                            <div
                                class="mb-3 text-xs font-bold text-green-600 bg-green-100 px-3 py-2 rounded-lg flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session()->has('error'))
                            <div
                                class="mb-3 text-xs font-bold text-red-600 bg-red-100 px-3 py-2 rounded-lg flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- Form Input --}}
                        @if (!$applied_coupon_code)
                            <form wire:submit.prevent="applyCoupon">
                                <label class="block text-xs  text-gray-500 uppercase mb-2 tracking-wide">Promo
                                    Code</label>
                                <div class="flex gap-2 items-stretch h-10">
                                    <input type="text" wire:model="coupon_code" placeholder="EX: DISC2025"
                                        class="flex-1 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#6B4226] focus:border-[#6B4226] block px-3 uppercase placeholder-gray-400 w-full h-full">

                                    <button type="submit" wire:loading.attr="disabled"
                                        class="bg-[#6B4226] hover:bg-[#5D3A20] text-white  text-sm px-5 rounded-lg transition duration-300 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed h-full whitespace-nowrap">
                                        <span wire:loading.remove wire:target="applyCoupon">APPLY</span>
                                        <span wire:loading wire:target="applyCoupon">...</span>
                                    </button>
                                </div>
                            </form>
                        @else
                            {{-- Tampilan Kupon Terpasang --}}
                            <div
                                class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <div>
                                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wide">Applied Code
                                    </p>
                                    <p class="text-sm font-bold text-gray-900">{{ $applied_coupon_code }}</p>
                                </div>
                                <button wire:click="removeCoupon"
                                    class="text-red-500 hover:text-red-700 text-xs font-bold underline decoration-2 underline-offset-2">
                                    Remove
                                </button>
                            </div>
                        @endif
                    </div>
                    {{-- Tombol Checkout --}}
                    @if (count($cart_items) > 0)
                        <a href="/checkout"
                            class="block w-full bg-[#6B4226] hover:bg-[#5D3A20] text-white text-center font-bold py-4 rounded-full shadow-lg hover:shadow-xl transition duration-300 transform active:scale-[0.98]">
                            Checkout Now
                        </a>
                    @endif

                    <p class="text-center text-xs text-gray-400 mt-6 flex justify-center items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                        Secure Checkout
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>





