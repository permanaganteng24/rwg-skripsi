<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Title;
use Livewire\Component;

class CartPage extends Component
{
    #[Title('Keranjang Belanja - Rizqi Wood')]

    public $cart_items = [];
    public $grand_total = 0;
    public $subtotal = 0;

    public $coupon_code;
    public $applied_coupon_code = null;
    public $discount = 0;

    public function mount()
    {
        $this->cart_items = CartManagement::getCartItemsFromCookie();
        
        // Load kupon dari session
        if (Session::has('coupon')) {
            $sessionCoupon = Session::get('coupon');
            $this->coupon_code = $sessionCoupon['code'];
            $this->applied_coupon_code = $sessionCoupon['code'];
        }

        $this->calculateTotal();
    }

    public function applyCoupon()
    {
        $this->resetErrorBag(); 

        $coupon = Coupon::where('code', $this->coupon_code)->first();

        // --- VALIDASI ---
        if (!$coupon) {
            session()->flash('error', 'Kode kupon tidak valid!');
            return;
        }

        if (!$coupon->is_active) {
            session()->flash('error', 'Kupon tidak aktif.');
            return;
        }

        if ($coupon->expiry_date && Carbon::now()->gt(Carbon::parse($coupon->expiry_date))) {
            session()->flash('error', 'Kupon sudah kadaluarsa.');
            return;
        }

        // Hitung subtotal sementara
        $tempSubtotal = array_sum(array_column($this->cart_items, 'total_amount'));

        if ($tempSubtotal < ($coupon->min_spend ?? 0)) {
            session()->flash('error', 'Min. belanja: Rp ' . number_format($coupon->min_spend, 0, ',', '.'));
            return;
        }

        // --- SIMPAN SESSION ---
        Session::put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'min_spend' => $coupon->min_spend,
        ]);

        $this->applied_coupon_code = $coupon->code;
        $this->calculateTotal();
        
        session()->flash('success', 'Kupon berhasil digunakan!');
    }

    public function removeCoupon()
    {
        Session::forget('coupon');
        $this->coupon_code = '';
        $this->applied_coupon_code = null;
        $this->discount = 0;
        
        $this->calculateTotal();
        session()->flash('success', 'Kupon dilepas.');
    }

    public function incrementQty($product_id)
    {
        $this->cart_items = CartManagement::addItemToCartWithQty($product_id, 1);
        $this->calculateTotal();
    }

    public function decrementQty($product_id)
    {
        $this->cart_items = CartManagement::addItemToCartWithQty($product_id, -1);
        $this->calculateTotal();
    }

    public function removeItem($product_id)
    {
        $this->cart_items = CartManagement::removeCartItem($product_id);
        $this->calculateTotal();
        $this->dispatch('cart-updated', total_count: count($this->cart_items));
    }

    public function calculateTotal()
    {
        $this->subtotal = 0;
        foreach ($this->cart_items as $item) {
            $this->subtotal += $item['total_amount'];
        }

        $this->discount = 0;
        
        if (Session::has('coupon')) {
            $coupon = Session::get('coupon');
            
            // Validasi ulang min spend saat update cart
            if ($this->subtotal < ($coupon['min_spend'] ?? 0)) {
                Session::forget('coupon');
                $this->applied_coupon_code = null;
            } else {
                $this->applied_coupon_code = $coupon['code'];
                if ($coupon['type'] == 'fixed') {
                    $this->discount = $coupon['value'];
                } else {
                    $this->discount = $this->subtotal * ($coupon['value'] / 100);
                }
                
                if($this->discount > $this->subtotal) $this->discount = $this->subtotal;
            }
        }

        $this->grand_total = max($this->subtotal - $this->discount, 0);
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}