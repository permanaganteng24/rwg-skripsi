<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Helpers\CartManagement;
use Jantinn\LivewireAlert\LivewireAlert;

class ProductDetail extends Component
{

    public $slug;
    public $quantity = 1;

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function incrementQty()
    {
        $product = Product::where('slug', $this->slug)->first();
        if ($product && $this->quantity < $product->stock) {
            $this->quantity++;
        }
    }

    public function decrementQty()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        $product = Product::where('slug', $this->slug)->first();

        // Validasi jika produk tidak ditemukan
        if (!$product) {
            return;
        }

        // Validasi Stok
        if ($product->stock <= 0) {
            $this->dispatch('alert', type: 'error', message: 'Stok barang habis!');
            return;
        }

        $total_count = CartManagement::addItemToCartWithQty($product->id, $this->quantity);
        $this->dispatch('cart-updated', total_count: $total_count);
        $this->dispatch('alert', type: 'success', message: 'Berhasil masuk keranjang!');
    }

    #[Title('Detail Produk')]
    public function render()
    {
        $product = Product::where('slug', $this->slug)
            ->with(['galleries', 'categories', 'reviews.user'])
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::whereHas('categories', function ($query) use ($product) {
            $query->whereIn('categories.id', $product->categories->pluck('id'));
        })
        ->where('id', '!=', $product->id)
        ->where('is_active', true)
        ->take(4)
        ->get();

        return view('livewire.product-detail', [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }
}