<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-xl w-full text-center bg-white border border-gray-200 rounded-xl shadow-sm p-10">

        @if($order->order_status == 'waiting_quote')
            <div class="inline-flex justify-center items-center w-20 h-20 rounded-full bg-amber-100 mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="block text-3xl font-bold text-gray-800 font-serif">Order Received!</h1>
            <p class="mt-2 text-gray-500">
                Since your address is outside our local coverage area, our admin is calculating the best shipping cost for you.
                <br>Please wait for confirmation via WhatsApp.
            </p>

        @else
            <div class="inline-flex justify-center items-center w-20 h-20 rounded-full bg-green-100 mb-5">
                <svg class="w-10 h-10 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="block text-3xl font-bold text-gray-800 font-serif">Ready to pay!</h1>
            <p class="mt-2 text-gray-500">Your order is now final. Please proceed with payment.</p>
        @endif

        <div class="mt-8 bg-stone-50 border border-dashed border-stone-300 rounded-lg p-6">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-2">Order Code:</p>
            <p class="text-4xl font-mono font-bold text-amber-800 select-all tracking-wider">{{ $order->code }}</p>
            
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-600">Total Bill:</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="mt-8 space-y-3">
            @if(in_array($order->order_status, ['new', 'waiting_payment']))
                <button wire:click="payNow" wire:loading.attr="disabled" class="w-full py-3 px-4 inline-flex justify-center items-center gap-2 rounded-lg border border-transparent font-bold bg-[#6B4226] text-white hover:bg-[#5D3A20] focus:outline-none transition shadow-lg disabled:opacity-50">
                    <span wire:loading.remove wire:target="payNow">Pay Now</span>
                    <span wire:loading wire:target="payNow">Loading...</span>
                </button>
            @elseif($order->order_status == 'waiting_quote')
                <a href="https://wa.me/6281234567890?text=Halo Admin, saya mau tanya status order {{ $order->code }}" target="_blank" class="w-full py-3 px-4 inline-flex justify-center items-center gap-2 rounded-lg border border-green-600 font-bold text-green-700 hover:bg-green-50 focus:outline-none transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.592 2.654-.696c1.001.575 1.901.889 2.806.891 3.182 0 5.768-2.587 5.768-5.766.001-3.187-2.575-5.773-5.768-5.774z" /></svg>
                    Contact the Admin via WhatsApp
                </a>
            @endif

            <a href="/" class="block w-full py-3 px-4 text-center rounded-lg border border-gray-200 font-medium text-gray-700 hover:bg-gray-50 transition">
                Back to Home
            </a>
        </div>
    </div>

    <!-- SCRIPT MIDTRANS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-snap-popup', (event) => {
                snap.pay(event.token, {
                    onSuccess: function(result) {
                        Livewire.dispatch('payment-success', { data: result });
                    },
                    onPending: function(result) {
                        alert("Waiting for payment! Please save your Virtual Account number.");
                    },
                    onError: function(result) {
                        alert("Payment failed or expired!");
                    },
                    onClose: function() {
                        alert('You closed the popup before completing the payment.');
                    }
                });
            });

            Livewire.on('midtrans-error', (event) => {
                alert("GAGAL KE MIDTRANS!\n\nAlasan: " + event.message);
            });
        });
    </script>
</div>