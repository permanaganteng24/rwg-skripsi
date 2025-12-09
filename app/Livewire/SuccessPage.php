<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;

use Midtrans\Config;
use Midtrans\Snap;
use Livewire\Attributes\On;

class SuccessPage extends Component
{
    #[Title('Order Berhasil - Rizqi Wood')]

    public $order;
    public $order_id; 
    public $snapToken;

    public function mount($order_id)
    {
        $this->order = Order::findOrFail($order_id);
    }

    // function payNow
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
            ]
        ];

        try {
            $this->snapToken = Snap::getSnapToken($params);
            $this->dispatch('show-snap-popup', token: $this->snapToken);
        } catch (\Exception $e) {
            $this->dispatch('midtrans-error', message: $e->getMessage());
        }
    }

    // function sukses
    #[On('payment-success')]
    public function handlePaymentSuccess($data)
    {
        $order = \App\Models\Order::find($this->order_id);

        if ($order) {
            $order->update([
                'order_status' => 'processing',
                'payment_status' => 'paid',
            ]);

            session()->flash('success', 'Payment successful! Your order is being processed.');
            return redirect()->to('/my-orders');
        }
    }

    public function render()
    {
        return view('livewire.success-page');
    }
}