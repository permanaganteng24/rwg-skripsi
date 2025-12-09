<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Helpers\CartManagement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session; 
use Livewire\Attributes\Title;
use Livewire\Component;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;

class CheckoutPage extends Component
{
    #[Title('Checkout - Rizqi Wood Gallery')]

    public $first_name;
    public $last_name;
    public $company_name;
    public $email;
    public $phone;

    // Alamat
    public $location_type = 'indonesia';
    public $address;
    public $zip_code;
    public $notes;

    // Dropdown Data
    public $provinces = [], $cities = [], $districts = [];
    public $selectedProvince, $selectedCity, $selectedDistrict;

    // Manual Data (International)
    public $manual_country_name, $manual_state, $manual_city;

    // Cart & Payment Info
    public $cart_items = [];
    public $subtotal = 0;
    public $grand_total = 0;
    public $shipping_cost = 0;
    
    // Variabel Tambahan untuk Kupon
    public $discount = 0;
    public $applied_coupon_code = null;

    public $is_lombok = false;
    public $shipping_status_text = 'Select city to calculate';

    public function mount()
    {
        $this->cart_items = CartManagement::getCartItemsFromCookie();
        if (count($this->cart_items) == 0) return redirect()->route('products.index');

        if (Auth::check()) {
            $user = Auth::user();
            $names = explode(' ', $user->name, 2);
            $this->first_name = $names[0] ?? '';
            $this->last_name = $names[1] ?? '';
            $this->email = $user->email;
        }
        
        $this->provinces = Province::pluck('name', 'code');
        $this->calculateTotals(); 
    }

    // --- LOGIC DROPDOWN & ONGKIR ---
    public function updatedLocationType($value) {
        $this->resetAddressFields();
        if($value == 'international') {
            $this->is_lombok = false;
            $this->shipping_status_text = 'Cargo (Pending Confirmation)';
        }
        $this->calculateTotals();
    }

    public function resetAddressFields() {
        $this->selectedProvince = null; $this->selectedCity = null; $this->selectedDistrict = null;
        $this->cities = []; $this->districts = [];
        $this->manual_country_name = ''; $this->manual_state = ''; $this->manual_city = '';
    }

    public function updatedSelectedProvince($value) {
        $this->cities = City::where('province_code', $value)->pluck('name', 'code');
        $this->selectedCity = null; $this->selectedDistrict = null;
    }

    public function updatedSelectedCity($value) {
        $this->districts = District::where('city_code', $value)->pluck('name', 'code');

        $cityName = City::where('code', $value)->value('name');
        if ($cityName && (str_contains(strtoupper($cityName), 'LOMBOK') || str_contains(strtoupper($cityName), 'MATARAM'))) {
            $this->is_lombok = true;
            $this->shipping_status_text = 'Free Local Shipping';
        } else {
            $this->is_lombok = false;
            $this->shipping_status_text = 'Cargo (Pending Confirmation)';
        }
        $this->calculateTotals();
    }

    // --- LOGIC HITUNG TOTAL ---
    public function calculateTotals() {
        $this->subtotal = 0;
        foreach ($this->cart_items as $item) {
            $this->subtotal += $item['total_amount'];
        }

        $this->discount = 0;
        $this->applied_coupon_code = null;

        if (Session::has('coupon')) {
            $coupon = Session::get('coupon');
            
            if ($this->subtotal >= ($coupon['min_spend'] ?? 0)) {
                $this->applied_coupon_code = $coupon['code'];
                
                if ($coupon['type'] == 'fixed') {
                    $this->discount = $coupon['value'];
                } else {
                    $this->discount = $this->subtotal * ($coupon['value'] / 100);
                }
            } else {
                Session::forget('coupon');
            }
        }
        if($this->discount > $this->subtotal) {
            $this->discount = $this->subtotal;
        }
        $this->shipping_cost = 0; 
        $this->grand_total = ($this->subtotal - $this->discount) + $this->shipping_cost;
    }

    public function placeOrder()
    {
        $this->validate([
            'first_name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'email' => 'required|email',
        ]);

        $country = ''; $province = ''; $city = ''; $district = '';

        if ($this->location_type === 'indonesia') {
            $this->validate(['selectedProvince' => 'required', 'selectedCity' => 'required']);
            $country = 'Indonesia';
            $province = Province::where('code', $this->selectedProvince)->value('name');
            $city = City::where('code', $this->selectedCity)->value('name');
            $district = District::where('code', $this->selectedDistrict)->value('name') ?? '-';
        } else {
            $this->validate(['manual_country_name' => 'required', 'manual_city' => 'required']);
            $country = $this->manual_country_name;
            $province = $this->manual_state;
            $city = $this->manual_city;
            $district = '-';
        }

        $orderStatus = $this->is_lombok ? 'waiting_payment' : 'waiting_quote';
        $shippingMethod = $this->is_lombok ? 'Free Local Shipping' : 'Cargo (Pending Confirmation)';

        $this->calculateTotals();

        // Save Order
        $order = Order::create([
            'user_id' => Auth::id(),
            'code' => 'ORD-' . strtoupper(uniqid()),
            'shipping_name' => $this->first_name . ' ' . $this->last_name,
            'company_name' => $this->company_name,
            'shipping_email' => $this->email,
            'shipping_phone' => $this->phone,

            'shipping_country' => $country,
            'shipping_province' => $province,
            'shipping_city' => $city,
            'shipping_district' => $district,
            'shipping_postal_code' => $this->zip_code,
            'shipping_address' => $this->address,

            'shipping_method' => $shippingMethod,
            'shipping_price' => 0, 
            
            // --- UPDATE PAYMENT---
            'total_product_price' => $this->subtotal,
            'discount_amount' => $this->discount,
            'grand_total' => $this->grand_total,
            
            'order_status' => $orderStatus,
            'payment_status' => 'unpaid',
            'notes' => $this->notes,
        ]);

        foreach ($this->cart_items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_name' => $item['name'],
                'quantity' => $item['quantity'],
                'price_per_unit' => $item['price'],
                'subtotal' => $item['total_amount'],
            ]);
        }

        CartManagement::clearCartItems();
        Session::forget('coupon'); 

        return redirect()->route('success', ['order_id' => $order->id]);
    }

    public function render()
    {
        return view('livewire.checkout-page');
    }
}