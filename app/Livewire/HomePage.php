<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Livewire\Attributes\Title;
use Livewire\Component;

class HomePage extends Component
{
    public function addToCart($product_id)
    {
        $total_count = CartManagement::addItemToCart($product_id);

        $this->dispatch('cart-updated', total_count: $total_count);
        $this->dispatch('alert', type: 'success', message: 'Berhasil masuk keranjang!');
    }

    #[Title('Home - Rizqi Wood Gallery')]
    public function render()
    {
        $categories = Category::withCount('products')->take(4)->get();

        $products = Product::where('is_active', true)
            ->with('galleries')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('is_featured')
            ->latest()
            ->take(4)
            ->get();

        $reviews = Review::where('is_approved', true)
            ->with('user')
            ->latest()
            ->take(4)
            ->get();

        return view('livewire.home-page', [
            'categories' => $categories,
            'products' => $products,
            'reviews' => $reviews,
        ]);
    }
}