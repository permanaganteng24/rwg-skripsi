<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Livewire\Attributes\Title;
use Livewire\Component;

class HomePage extends Component
{
    #[Title('Home - Rizqi Wood Gallery')] 
    public function render()
    {
        $categories = Category::take(4)->get();

        $products = Product::where('is_active', true)
            ->with('galleries') 
            ->latest()
            ->take(8)
            ->get();

        $reviews = Review::where('is_approved', true) 
            ->with('user') 
            ->latest()
            ->take(6)
            ->get();

        return view('livewire.home-page', [
            'categories' => $categories,
            'products' => $products,
            'reviews' => $reviews, 
        ]);
    }
}