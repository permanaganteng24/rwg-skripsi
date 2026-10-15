<?php

namespace App\Helpers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CartManagement
{
    // === MENGAMBIL DATA CART ===
    static public function getCartItemsFromCookie() 
    {
        // A. JIKA USER LOGIN -> AMBIL DARI DATABASE
        if (Auth::check()) {
            $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();
            $finalItems = [];

            foreach ($cartItems as $item) {
                if ($item->product) { 
                    $finalItems[] = [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'name' => $item->product->name,
                        'slug' => $item->product->slug,
                        'image' => $item->product->galleries->first()->image_url ?? '',
                        'price' => $item->product->price,
                        'quantity' => $item->quantity,
                        'total_amount' => $item->product->price * $item->quantity
                    ];
                }
            }
            return $finalItems;
        }

        // B. JIKA GUEST -> AMBIL DARI COOKIE
        $cart_items = json_decode(Cookie::get('cart_items'), true);
        if (!$cart_items) {
            $cart_items = [];
        }
        return $cart_items;
    }

    // === TAMBAH 1 ITEM ===
    static public function addItemToCart($product_id)
    {
        return self::addItemToCartWithQty($product_id, 1);
    }

    // === TAMBAH DENGAN QTY ===
    static public function addItemToCartWithQty($product_id, $qty = 1)
    {
        // A. JIKA USER LOGIN -> SIMPAN KE DB
        if (Auth::check()) {
            return self::addItemToCartForUser($product_id, $qty);
        }

        // B. JIKA GUEST -> SIMPAN KE COOKIE
        return self::addItemToCartForGuest($product_id, $qty);
    }

    // --- Penanganan pengguna login ---
    static private function addItemToCartForUser($product_id, $qty)
    {
        $user_id = Auth::id();
        $existingItem = CartItem::where('user_id', $user_id)
                                ->where('product_id', $product_id)
                                ->first();

        if ($existingItem) {
            $existingItem->quantity = self::normalizeQuantity($existingItem->quantity + $qty);
            $existingItem->save();
        } else {
            CartItem::create([
                'user_id' => $user_id,
                'product_id' => $product_id,
                'quantity' => $qty > 0 ? $qty : 1
            ]);
        }

        return self::getCartItemsFromCookie();
    }

    // --- Penanganan pengguna tamu (guest) ---
    static private function addItemToCartForGuest($product_id, $qty)
    {
        $cart_items = self::getCartItemsFromCookie();
        $existing_key = self::findCartItemKey($cart_items, $product_id);

        if ($existing_key !== null) {
            $cart_items[$existing_key] = self::updateGuestCartItemQty($cart_items[$existing_key], $qty);
        } else {
            $newItem = self::buildGuestCartItem($product_id, $qty);
            if ($newItem) {
                $cart_items[] = $newItem;
            }
        }

        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    static private function findCartItemKey($cart_items, $product_id)
    {
        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                return $key;
            }
        }
        return null;
    }

    static private function updateGuestCartItemQty($item, $qty)
    {
        $item['quantity'] = self::normalizeQuantity($item['quantity'] + $qty);
        $item['total_amount'] = $item['quantity'] * $item['price'];
        return $item;
    }

    static private function buildGuestCartItem($product_id, $qty)
    {
        $product = Product::with('galleries')->find($product_id);

        if (!$product) {
            return null;
        }

        return [
            'product_id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'image' => $product->galleries->first()->image_url ?? '',
            'price' => $product->price,
            'quantity' => $qty,
            'total_amount' => $product->price * $qty
        ];
    }

    // Jumlah item di keranjang minimal 1
    static private function normalizeQuantity($quantity)
    {
        return $quantity < 1 ? 1 : $quantity;
    }

    // === HAPUS ITEM ===
    static public function removeCartItem($product_id)
    {
        // A. JIKA LOGIN -> HAPUS DARI DB
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->where('product_id', $product_id)->delete();
            return self::getCartItemsFromCookie();
        }

        // B. JIKA GUEST -> HAPUS DARI COOKIE
        $cart_items = self::getCartItemsFromCookie();
        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                unset($cart_items[$key]);
            }
        }
        $cart_items = array_values($cart_items);
        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    // === HELPERS ===
    static public function addCartItemsToCookie($cart_items)
    {
        Cookie::queue('cart_items', json_encode($cart_items), 60 * 24 * 30);
    }

    static public function clearCartItems()
    {
        if(Auth::check()){
             CartItem::where('user_id', Auth::id())->delete();
        }
        Cookie::queue(Cookie::forget('cart_items'));
    }
    
    // Helper tambahan untuk Filament / Navigasi
    static public function getCartItemsCount()
    {
        $cart_items = self::getCartItemsFromCookie();
        return count($cart_items);
    }
}