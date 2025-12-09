<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Attributes\Title;
use Livewire\Component;
use Midtrans\Config;
use Midtrans\Snap;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class MyOrderDetailPage extends Component
{
    #[Title('Detail Pesanan - Rizqi Wood Gallery')]

    public $order_id;
    public $snapToken;

    public function payNow()
    {
        $order = \App\Models\Order::find($this->order_id);

        if (!$order) {
            $this->dispatch('midtrans-error', message: 'Order data not found!');
            return;
        }

        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);

        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => [], 
        ];

        $params = [
            'transaction_details' => [
                'order_id' => $order->code . '-' . time(), 
                'gross_amount' => (int) $order->grand_total, 
            ],
            'customer_details' => [
                'first_name' => $order->shipping_name ?? 'Customer',
                'email' => $order->shipping_email ?? 'customer@example.com',
                'phone' => $order->shipping_phone ?? '08123456789',
            ],
        ];

        try {
            $this->snapToken = Snap::getSnapToken($params);
            $this->dispatch('show-snap-popup', token: $this->snapToken);
            
        } catch (\Exception $e) {
            $this->dispatch('midtrans-error', message: $e->getMessage());
        }
    }

    #[On('payment-success')]
    public function handlePaymentSuccess($data)
    {
        $order = \App\Models\Order::find($this->order_id);
        
        if ($order) {
            $order->update([
                'order_status' => 'processing',
                'payment_status' => 'paid', 
            ]);

            session()->flash('success', 'Payment has been automatically confirmed.');
            
            return redirect()->to(request()->header('Referer'));
        }
    }

    public function mount($order_id)
    {
        $this->order_id = $order_id;
    }

    public function render()
    {
        $order = Order::where('id', $this->order_id)
            ->where('user_id', auth()->id())
            ->with('items.product')
            ->firstOrFail();

        return view('livewire.my-order-detail-page', [
            'order' => $order
        ]);
    }
}