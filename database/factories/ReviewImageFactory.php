<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReviewImage>
 */
class ReviewImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'review_id' => Review::factory(),
            'image_url' => 'reviews/test-review.jpg',
        ];
    }
}
