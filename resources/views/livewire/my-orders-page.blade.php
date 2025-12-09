<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto bg-stone-50 min-h-screen">
    <div class="mb-8">
        <h1 class="text-3xl font-serif font-bold text-gray-900">My Orders</h1>
        <p class="text-gray-600 mt-2">Track and manage your order history</p>
    </div>

    @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach ($orders as $order)
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition overflow-hidden">
                    <!-- Order Header -->
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-6">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-medium">Order ID</p>
                                <p class="text-sm font-bold text-gray-900">{{ $order->code }}</p>
                            </div>
                            <div class="hidden sm:block h-8 w-px bg-gray-300"></div>
                            <div class="hidden sm:block">
                                <p class="text-xs text-gray-500 uppercase font-medium">Date</p>
                                <p class="text-sm text-gray-700">{{ $order->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            @php
                                $status = $order->order_status;
                                $badgeColor = match($status) {
                                    'new' => 'bg-blue-100 text-blue-800',
                                    'processing' => 'bg-yellow-100 text-yellow-800',
                                    'shipped' => 'bg-purple-100 text-purple-800',
                                    'delivered' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    'waiting_quote' => 'bg-gray-100 text-gray-800',
                                    'waiting_payment' => 'bg-amber-100 text-amber-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-bold uppercase {{ $badgeColor }}">
                                {{ str_replace('_', ' ', ucfirst($status)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Order Content -->
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <!-- Product Preview -->
                            <div class="flex items-start gap-4 flex-1">
                                @php
                                    $firstItem = $order->items->first();
                                    $remainingCount = $order->items->count() - 1;
                                    $image = $firstItem->product->galleries->first()->image_url ?? null;
                                @endphp
                                <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-lg border border-gray-200 bg-gray-100">
                                    <img src="{{ $image ? asset('storage/'.$image) : 'https://via.placeholder.com/100' }}" class="h-full w-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-bold text-gray-900 line-clamp-2">{{ $firstItem->product_name }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Qty: {{ number_format($firstItem->quantity) }}
                                        @if($remainingCount > 0)
                                            <span class="text-gray-400">and {{ $remainingCount }} more item{{ $remainingCount > 1 ? 's' : '' }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Order Total & Action -->
                            <div class="flex items-center justify-between lg:justify-end gap-6 pt-4 lg:pt-0 border-t lg:border-t-0 border-gray-100">
                                <div class="text-right">
                                    <p class="text-xs text-gray-500 uppercase font-medium mb-1">Total Amount</p>
                                    <p class="text-lg font-bold text-gray-900">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                                </div>
                                <a href="/my-orders/{{ $order->id }}" class="inline-flex items-center gap-2 text-amber-700 hover:text-amber-800 font-bold border-2 border-amber-600 hover:border-amber-700 px-5 py-2.5 rounded-lg hover:bg-amber-50 transition whitespace-nowrap">
                                    View Details
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $orders->links() }}
        </div>

    @else
        <!-- Empty State -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-12 text-center">
            <div class="max-w-md mx-auto">
                <div class="bg-gray-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No Orders Yet</h3>
                <p class="text-gray-500 mb-6">You haven't placed any orders. Start shopping to see your order history here.</p>
                <a href="/products" class="inline-flex items-center gap-2 bg-amber-700 text-white px-6 py-3 rounded-lg font-bold hover:bg-amber-800 transition shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    Start Shopping
                </a>
            </div>
        </div>
    @endif
</div>