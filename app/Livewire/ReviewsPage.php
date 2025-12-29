<?php

namespace App\Livewire;

use App\Models\Review;
use Livewire\Attributes\Title;
use Livewire\Component;

class ReviewsPage extends Component
{
    #[Title('Customer Reviews - Rizqi Wood Gallery')]

    public $filter = 'all';
    public $limit = 9;

    // Ganti filter
    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->limit = 9;
    }

    public function loadMore()
    {
        $this->limit += 6;
    }

    public function render()
    {
        $query = Review::where('is_approved', true)
            ->with(['user', 'product', 'images'])
            ->latest();

        // Filtering
        if ($this->filter === 'photos') {
            $query->has('images');
        } elseif ($this->filter === '5stars') {
            $query->where('rating', 5);
        }

        $reviews = $query->take($this->limit)->get();
        $totalReviewsCount = $query->count();

        // Hitung Statistik (Untuk Header)
        $stats = [
            'avg_rating' => Review::where('is_approved', true)->avg('rating'),
            'total_reviews' => Review::where('is_approved', true)->count(),
            'recommendation' => Review::where('is_approved', true)->count() > 0
                ? (Review::where('is_approved', true)->where('rating', '>=', 4)->count() / Review::where('is_approved', true)->count()) * 100
                : 0
        ];

        return view('livewire.reviews-page', [
            'reviews' => $reviews,
            'total_visible' => $reviews->count(),
            'total_available' => $totalReviewsCount,
            'stats' => $stats
        ]);
    }
}
