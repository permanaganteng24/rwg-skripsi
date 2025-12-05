<div class="bg-white font-sans text-gray-700">

    {{-- Hero Section --}}
    <div class="bg-stone-50 border-b border-stone-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            <p class="text-amber-800 font-bold uppercase text-xs tracking-widest mb-3">Guide</p>
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-gray-900 mb-4">How to Order</h1>
            <p class="text-gray-500 max-w-xl mx-auto leading-relaxed">
                A simple, secure, and straightforward process — from browsing to your doorstep.
            </p>
        </div>
    </div>

    {{-- Vertical Timeline --}}
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20">

        @php
            $steps = [
                [
                    'number' => '01',
                    'title'  => 'Browse Products & Add to Cart',
                    'body'   => 'Explore our curated collection of handcrafted teak wood furniture. Select the pieces you love and add them to your cart. You can adjust quantities or remove items before proceeding.',
                    'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.25a2.25 2.25 0 002.25-2.25V9a2.25 2.25 0 00-2.25-2.25H5.625c-.621 0-1.125.504-1.125 1.125v1.5M7.5 14.25L5.25 6.375" />',
                ],
                [
                    'number' => '02',
                    'title'  => 'Checkout & Fill Shipping Details',
                    'body'   => 'Proceed to checkout and complete your shipping information — your full name, delivery address, phone number, and any special notes for our team. Your data is always kept private and secure.',
                    'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />',
                ],
                [
                    'number' => '03',
                    'title'  => 'Complete Payment via Midtrans',
                    'body'   => 'Pay securely through Midtrans — our trusted payment gateway. We accept a wide range of methods including Virtual Account (BCA, Mandiri, BNI, BRI) and popular E-Wallets (GoPay, OVO, Dana, ShopeePay). Your transaction is fully encrypted.',
                    'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />',
                ],
                [
                    'number' => '04',
                    'title'  => 'Order Processing',
                    'body'   => 'Once your payment is confirmed, our team begins handcrafting and packaging your order with care. We will also calculate the real shipping cost (cargo) and update your order accordingly before dispatch.',
                    'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />',
                ],
                [
                    'number' => '05',
                    'title'  => 'Shipping & Delivery',
                    'body'   => 'Your furniture will be shipped via our trusted cargo partners with full tracking. We specialize in safe packing for heavy and fragile wood items to ensure your pieces arrive in perfect condition — whether locally or internationally.',
                    'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.375-4.5h-.375a1.5 1.5 0 01-1.5-1.5V5.625a1.5 1.5 0 011.5-1.5h2.25m0 0A2.25 2.25 0 019 6.375v1.5M4.5 14.25H15m-10.5 0v3.75m0-3.75H15m0 0h1.5a1.5 1.5 0 001.5-1.5v-4.5a1.5 1.5 0 00-1.5-1.5H15m0-3.75H9m6 3.75V9m0 5.25V9" />',
                ],
            ];
        @endphp

        <div class="relative">
            {{-- Vertical line --}}
            <div class="absolute left-8 top-0 bottom-0 w-px bg-stone-200 hidden sm:block"></div>

            <div class="space-y-12">
                @foreach($steps as $step)
                <div class="relative flex gap-6 sm:gap-10 items-start">

                    {{-- Step circle --}}
                    <div class="flex-shrink-0 w-16 h-16 rounded-full bg-[#6B4226] text-white flex items-center justify-center shadow-md relative z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                            {!! $step['icon'] !!}
                        </svg>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 pt-3 pb-8 border-b border-stone-100 last:border-b-0">
                        <span class="text-xs font-bold text-amber-700 uppercase tracking-widest">Step {{ $step['number'] }}</span>
                        <h2 class="text-xl font-serif font-bold text-gray-900 mt-1 mb-2">{{ $step['title'] }}</h2>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ $step['body'] }}</p>
                    </div>

                </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- CTA Banner --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="bg-stone-50 border border-stone-100 rounded-2xl p-10 md:p-16 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-2xl">
                <h2 class="text-2xl md:text-3xl font-serif font-bold text-gray-900 mb-3">Still have questions?</h2>
                <p class="text-gray-500 text-sm">Our team is happy to assist you with any inquiries about products, shipping, or custom orders.</p>
            </div>
            <a href="{{ route('contact') }}" class="flex-shrink-0 bg-[#6B4226] text-white font-bold py-4 px-8 rounded hover:bg-[#5D3A20] transition shadow-lg flex items-center gap-2">
                Contact Us
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
            </a>
        </div>
    </div>

</div>
