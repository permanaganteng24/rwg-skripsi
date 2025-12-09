<div class="bg-gray-50 font-sans text-gray-700 min-h-screen">
    
    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-200 py-6 mb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-2 text-sm text-gray-500">
            <a href="/" class="hover:text-amber-800 transition">Home</a>
            <span class="text-gray-400">/</span>
            <a href="/cart" class="hover:text-amber-800 transition">Cart</a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-900 font-bold">Checkout</span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <form wire:submit.prevent="placeOrder" class="lg:grid lg:grid-cols-12 lg:gap-x-12 xl:gap-x-16">
            
            {{-- KOLOM KIRI: FORM DATA DIRI & ALAMAT --}}
            <div class="lg:col-span-7 space-y-10">
                
                {{-- Billing Info --}}
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">Billing Information</h2>
                    
                    <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Full Name</label>
                            <div class="grid grid-cols-2 gap-4">
                                <input wire:model="first_name" type="text" placeholder="First name" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-1 focus:ring-amber-800 focus:border-amber-800 outline-none transition">
                                <input wire:model="last_name" type="text" placeholder="Last name" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-1 focus:ring-amber-800 focus:border-amber-800 outline-none transition">
                            </div>
                            @error('first_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Company Name (Optional)</label>
                            <input wire:model="company_name" type="text" placeholder="Company / Organization" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-1 focus:ring-amber-800 focus:border-amber-800 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Email Address</label>
                            <input wire:model="email" type="email" placeholder="email@example.com" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-1 focus:ring-amber-800 focus:border-amber-800 outline-none transition">
                            @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Phone Number</label>
                            <input wire:model="phone" type="text" placeholder="08..." class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-1 focus:ring-amber-800 focus:border-amber-800 outline-none transition">
                            @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- Shipping Address --}}
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">Shipping Address</h2>
                    
                    <div class="mb-6">
                        <label class="block text-xs font-medium text-gray-500 mb-2 uppercase tracking-wide">Destination</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer border p-3 rounded-lg flex-1 hover:bg-gray-50 {{ $location_type == 'indonesia' ? 'border-amber-600 bg-amber-50 ring-1 ring-amber-600' : 'border-gray-300' }}">
                                <input type="radio" wire:model.live="location_type" value="indonesia" class="text-amber-600 focus:ring-amber-600">
                                <span class="text-sm font-bold">Indonesia</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer border p-3 rounded-lg flex-1 hover:bg-gray-50 {{ $location_type == 'international' ? 'border-amber-600 bg-amber-50 ring-1 ring-amber-600' : 'border-gray-300' }}">
                                <input type="radio" wire:model.live="location_type" value="international" class="text-amber-600 focus:ring-amber-600">
                                <span class="text-sm font-bold">International</span>
                            </label>
                        </div>
                    </div>

                    @if($location_type === 'indonesia')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Province</label>
                                <select wire:model.live="selectedProvince" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm bg-white outline-none">
                                    <option value="">Select Province...</option>
                                    @foreach($provinces as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('selectedProvince') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">City / Regency</label>
                                <select wire:model.live="selectedCity" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm bg-white outline-none" @if(empty($cities)) disabled @endif>
                                    <option value="">Select City...</option>
                                    @foreach($cities as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('selectedCity') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">District</label>
                                <select wire:model.live="selectedDistrict" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm bg-white outline-none" @if(empty($districts)) disabled @endif>
                                    <option value="">Select District...</option>
                                    @foreach($districts as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Country Name</label>
                                <input wire:model="manual_country_name" type="text" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm outline-none">
                                @error('manual_country_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">State / Province</label>
                                <input wire:model="manual_state" type="text" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">City</label>
                                <input wire:model="manual_city" type="text" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm outline-none">
                                @error('manual_city') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Zip Code</label>
                            <input wire:model="zip_code" type="text" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Full Address</label>
                            <textarea wire:model="address" rows="2" placeholder="Street Name, House No, etc." class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm outline-none"></textarea>
                            @error('address') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Notes</label>
                            <textarea wire:model="notes" rows="1" placeholder="Optional notes regarding your order" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm outline-none"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: ORDER SUMMARY --}}
            <div class="lg:col-span-5 mt-12 lg:mt-0">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-xl shadow-gray-100 p-8 sticky top-24">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>

                    <div class="flow-root mb-8 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                        <ul class="divide-y divide-gray-100">
                            @foreach ($cart_items as $item)
                                <li class="flex py-4 gap-4">
                                    <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-100 bg-gray-50">
                                        <img src="{{ asset('storage/' . $item['image']) }}" class="h-full w-full object-cover">
                                    </div>
                                    <div class="flex flex-1 flex-col justify-center">
                                        <div class="flex justify-between text-sm font-medium text-gray-900">
                                            <h3 class="truncate max-w-[150px]">{{ $item['name'] }}</h3>
                                            <p class="whitespace-nowrap">Rp {{ number_format($item['total_amount'], 0, ',', '.') }}</p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Qty: {{ $item['quantity'] }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="border-t border-gray-100 pt-6 space-y-4">
                        {{-- SUBTOTAL --}}
                        <div class="flex justify-between text-sm text-gray-600">
                            <p>Subtotal</p>
                            <p class="font-medium text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                        </div>
                        
                        {{-- BAGIAN BARU: DISKON KUPON --}}
                        @if ($discount > 0)
                            <div class="flex justify-between text-sm text-green-700 bg-green-50 p-2 rounded items-center">
                                <span class="flex items-center gap-1">
                                    Diskon <span class="text-[10px] font-bold uppercase border border-green-200 px-1 rounded bg-white">{{ $applied_coupon_code }}</span>
                                </span>
                                <span class="font-bold">- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        {{-- SHIPPING --}}
                        <div class="flex justify-between text-sm items-start">
                            <p class="text-gray-600">Shipping</p>
                            <div class="text-right">
                                @if($is_lombok)
                                    <p class="font-bold text-green-600">Free (Lombok)</p>
                                    <p class="text-[10px] text-gray-400">Local Delivery</p>
                                @elseif($location_type === 'international')
                                    <p class="font-bold text-amber-600">Pending</p>
                                    <p class="text-[10px] text-gray-400">Calculated via Email</p>
                                @elseif($selectedCity)
                                    <p class="font-bold text-amber-600">Pending</p>
                                    <p class="text-[10px] text-gray-400">Cargo Quote Needed</p>
                                @else
                                    <span class="text-gray-400 text-xs italic">Select destination</span>
                                @endif
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        {{-- GRAND TOTAL --}}
                        <div class="flex justify-between items-center pt-2">
                            <p class="text-base font-bold text-gray-900">Total</p>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-amber-800">Rp {{ number_format($grand_total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" wire:loading.attr="disabled" class="w-full mt-8 bg-[#6B4226] text-white font-bold py-4 rounded-full shadow-lg hover:bg-[#5D3A20] hover:shadow-xl transition duration-300 transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center gap-2 group">
                        <span wire:loading.remove>Place Order</span>
                        <span wire:loading>Processing Order...</span>
                        <svg wire:loading.remove class="w-5 h-5 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                    
                    <p class="text-center text-xs text-gray-400 mt-4 flex justify-center items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Secure Checkout with SSL
                    </p>
                </div>
            </div>

        </form>
    </div>
</div>