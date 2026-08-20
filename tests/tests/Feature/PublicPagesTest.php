<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use App\Livewire\ContactPage;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render_successfully(): void
    {
        // US-001: melihat informasi Rizqi Wood Gallery, plus other public pages.
        $publicRoutes = [
            '/',
            '/about',
            '/products',
            '/contact',
            '/reviews',
            '/how-to-order',
        ];

        foreach ($publicRoutes as $route) {
            $this->get($route)->assertOk();
        }
    }

    public function test_authenticated_pages_redirect_guests_to_login(): void
    {
        $protectedRoutes = [
            '/checkout',
            '/my-orders',
        ];

        foreach ($protectedRoutes as $route) {
            $this->get($route)->assertRedirect('/login');
        }
    }

    public function test_contact_form_accepts_valid_message_and_validates_invalid_data(): void
    {
        // Invalid submission keeps validation errors.
        Livewire::test(ContactPage::class)
            ->set('name', 'Al')
            ->set('email', 'not-an-email')
            ->set('message', 'short')
            ->call('submitMessage')
            ->assertHasErrors(['name' => 'min', 'email' => 'email', 'message' => 'min']);

        // Valid submission succeeds and resets the form.
        Livewire::test(ContactPage::class)
            ->set('name', 'Andi Wijaya')
            ->set('email', 'andi@example.com')
            ->set('subject', 'Pertanyaan produk')
            ->set('message', 'Apakah rak buku ini tersedia dalam warna natural?')
            ->call('submitMessage')
            ->assertHasNoErrors()
            ->assertSet('name', null)
            ->assertSet('message', null);
    }
}
