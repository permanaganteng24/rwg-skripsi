<?php

namespace Tests\Feature;

use App\Livewire\ReviewModal;
use App\Livewire\ReviewsPage;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_review_with_photo_and_it_stays_pending(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        Livewire::test(ReviewModal::class)
            ->call('openModal', $product->id, $order->id, $product->name)
            ->set('rating', 5)
            ->set('comment', 'Kualitas kayu sangat bagus dan pengerjaan rapi.')
            ->set('photos', [UploadedFile::fake()->image('review.jpg')])
            ->call('saveReview')
            ->assertDispatched('review-saved');

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'order_id' => $order->id,
            'rating' => 5,
            'is_approved' => false,
        ]);

        $review = Review::first();
        $this->assertCount(1, $review->images);
    }

    public function test_review_form_validates_rating_comment_and_photo(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        Livewire::test(ReviewModal::class)
            ->call('openModal', $product->id, $order->id, $product->name)
            ->set('rating', 0)
            ->set('comment', str_repeat('a', 1001))
            ->set('photos', [UploadedFile::fake()->create('document.pdf', 100)])
            ->call('saveReview')
            ->assertHasErrors(['rating', 'comment', 'photos.0']);

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_only_approved_reviews_are_visible_to_customers(): void
    {
        $approved = Review::factory()->approved()->create(['comment' => 'Sangat memuaskan']);
        Review::factory()->create(['comment' => 'Belum disetujui admin', 'is_approved' => false]);

        Livewire::test(ReviewsPage::class)
            ->assertSee('Sangat memuaskan')
            ->assertDontSee('Belum disetujui admin');
    }

    public function test_reviews_page_filters_photos_and_five_star_reviews_and_loads_more(): void
    {
        $withPhotoRatingFive = Review::factory()->approved()->create(['rating' => 5, 'comment' => 'Review dengan foto']);
        $withPhotoRatingFive->images()->create(['image_url' => 'reviews/photo.jpg']);

        Review::factory()->approved()->create(['rating' => 3, 'comment' => 'Review tanpa foto']);

        // Filter: only reviews that have photos.
        Livewire::test(ReviewsPage::class)
            ->call('setFilter', 'photos')
            ->assertSee('Review dengan foto')
            ->assertDontSee('Review tanpa foto');

        // Filter: only five-star reviews.
        Livewire::test(ReviewsPage::class)
            ->call('setFilter', '5stars')
            ->assertSee('Review dengan foto')
            ->assertDontSee('Review tanpa foto');

        // Load more increases the visible limit.
        Livewire::test(ReviewsPage::class)
            ->assertSet('limit', 9)
            ->call('loadMore')
            ->assertSet('limit', 15);
    }
}
