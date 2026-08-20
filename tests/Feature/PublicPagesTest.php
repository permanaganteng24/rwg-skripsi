<?php

namespace Tests\Feature;

use App\Models\Review;
use App\Livewire\ContactPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render_successfully(): void
    {
        $user = $this->customerUser(['name' => 'Customer Reviewer']);
        $product = $this->product(['name' => 'Teak Lounge Chair', 'slug' => 'teak-lounge-chair']);

        Review::factory()->approved()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Kualitas kayu sangat baik.',
        ]);

        foreach (['/', '/about', '/contact', '/products', '/reviews'] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_authenticated_pages_redirect_guests_to_login(): void
    {
        $this->get('/checkout')->assertRedirect('/login');
        $this->get('/my-orders')->assertRedirect('/login');
        $this->get('/invoice/1')->assertRedirect('/login');
    }

    public function test_contact_form_accepts_valid_message_and_validates_invalid_data(): void
    {
        Livewire::test(ContactPage::class)
            ->set('name', 'Valid Customer')
            ->set('email', 'customer@example.com')
            ->set('subject', 'Product Question')
            ->set('message', 'Saya ingin bertanya tentang produk custom.')
            ->call('submitMessage')
            ->assertSet('name', null)
            ->assertSet('email', null)
            ->assertSet('subject', null)
            ->assertSet('message', null)
            ->assertDispatched('alert');

        Livewire::test(ContactPage::class)
            ->set('name', 'ab')
            ->set('email', 'invalid-email')
            ->set('message', 'short')
            ->call('submitMessage')
            ->assertHasErrors([
                'name' => 'min',
                'email' => 'email',
                'message' => 'min',
            ]);
    }
}
