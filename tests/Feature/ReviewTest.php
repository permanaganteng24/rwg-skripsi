<?php

namespace Tests\Feature;

use App\Livewire\ReviewModal;
use App\Livewire\ReviewsPage;
use App\Models\Review;
use App\Models\ReviewImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_review_with_photo_and_it_stays_pending(): void
    {
        Storage::fake('public');

        $user = $this->customerUser();
        $product = $this->product(['name' => 'Review Chair']);
        $order = $this->orderFor($user, [], $product);

        $this->actingAs($user);

        Livewire::test(ReviewModal::class)
            ->call('openModal', $product->id, $order->id, $product->name)
            ->assertSet('isModalOpen', true)
            ->set('rating', 5)
            ->set('comment', 'Produk sangat bagus dan rapi.')
            ->set('photos', [UploadedFile::fake()->image('review.jpg')])
            ->call('saveReview')
            ->assertSet('isModalOpen', false)
            ->assertDispatched('review-saved');

        $review = Review::query()->where('product_id', $product->id)->first();

        $this->assertNotNull($review);
        $this->assertFalse((bool) $review->is_approved);
        $this->assertDatabaseHas('review_images', ['review_id' => $review->id]);
    }

    public function test_review_form_validates_rating_comment_and_photo(): void
    {
        Storage::fake('public');

        $user = $this->customerUser();
        $product = $this->product();
        $order = $this->orderFor($user, [], $product);

        $this->actingAs($user);

        Livewire::test(ReviewModal::class)
            ->call('openModal', $product->id, $order->id, $product->name)
            ->set('rating', 0)
            ->set('comment', str_repeat('a', 1001))
            ->set('photos', [UploadedFile::fake()->image('large-review.jpg')->size(3000)])
            ->call('saveReview')
            ->assertHasErrors([
                'rating' => 'min',
                'comment' => 'max',
                'photos.0' => 'max',
            ]);
    }

    public function test_only_approved_reviews_are_visible_to_customers(): void
    {
        $product = $this->product(['slug' => 'review-visible-chair']);
        $user = $this->customerUser(['name' => 'Reviewer']);

        Review::factory()->approved()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'comment' => 'Review yang sudah approved.',
        ]);

        Review::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'comment' => 'Review pending tidak tampil.',
            'is_approved' => false,
        ]);

        $this->get('/reviews')
            ->assertOk()
            ->assertSee('Review yang sudah approved.')
            ->assertDontSee('Review pending tidak tampil.');

        $this->get('/products/' . $product->slug)
            ->assertOk()
            ->assertSee('Review yang sudah approved.')
            ->assertDontSee('Review pending tidak tampil.');
    }

    public function test_reviews_page_filters_photos_and_five_star_reviews_and_loads_more(): void
    {
        $user = $this->customerUser();
        $product = $this->product();

        $photoReview = Review::factory()->approved()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Review dengan foto.',
        ]);

        ReviewImage::factory()->create(['review_id' => $photoReview->id]);

        Review::factory()->approved()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 3,
            'comment' => 'Review tanpa foto.',
        ]);

        Livewire::test(ReviewsPage::class)
            ->call('setFilter', 'photos')
            ->assertSet('filter', 'photos')
            ->assertSee('Review dengan foto.')
            ->assertDontSee('Review tanpa foto.')
            ->call('setFilter', '5stars')
            ->assertSet('filter', '5stars')
            ->assertSee('Review dengan foto.')
            ->call('loadMore')
            ->assertSet('limit', 15);
    }
}
