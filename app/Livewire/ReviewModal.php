<?php

namespace App\Livewire;

use App\Models\Review;
use App\Models\ReviewImage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class ReviewModal extends Component
{
    use WithFileUploads;

    public $isModalOpen = false;
    public $product_id;
    public $order_id;
    public $product_name;
    
    // Form Inputs
    public $rating = 5;
    public $comment = '';
    public $photos = [];

    #[On('open-review-modal')]
    public function openModal($product_id, $order_id, $product_name)
    {
        $this->product_id = $product_id;
        $this->order_id = $order_id;
        $this->product_name = $product_name;
        $this->rating = 0;
        $this->comment = '';
        $this->photos = [];
        $this->isModalOpen = true;
    }

    public function closeReviewModal()
    {
        $this->isModalOpen = false;
        $this->photos = []; 
    }

    public function setRating($value)
    {
        $this->rating = $value;
    }

    public function saveReview()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'photos.*' => 'image|max:2048', 
        ]);

        $review = Review::create([
            'user_id' => auth()->id(),
            'product_id' => $this->product_id,
            'order_id' => $this->order_id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'is_approved' => false, 
        ]);

        if (!empty($this->photos)) {
            foreach ($this->photos as $photo) {
                $path = $photo->store('reviews', 'public');
                ReviewImage::create([
                    'review_id' => $review->id,
                    'image_url' => $path,
                ]);
            }
        }

        $this->isModalOpen = false;
        session()->flash('success', 'Review successfully submitted! Awaiting admin approval.');
        $this->dispatch('review-saved');
    }

    public function render()
    {
        return view('livewire.review-modal');
    }
}