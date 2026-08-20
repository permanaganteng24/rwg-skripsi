<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_register_and_is_logged_in_automatically(): void
    {
        Livewire::test(RegisterPage::class)
            ->set('name', 'Budi Santoso')
            ->set('email', 'budi@example.com')
            ->set('password', 'password123')
            ->call('register')
            ->assertRedirect('/checkout');

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'budi@example.com',
            'name' => 'Budi Santoso',
        ]);
    }

    public function test_registration_validates_required_fields_and_unique_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        Livewire::test(RegisterPage::class)
            ->set('name', '')
            ->set('email', 'existing@example.com')
            ->set('password', '123')
            ->call('register')
            ->assertHasErrors(['name' => 'required', 'email' => 'unique', 'password' => 'min']);

        $this->assertGuest();
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'customer@example.com',
            'password' => Hash::make('secret123'),
        ]);

        Livewire::test(LoginPage::class)
            ->set('email', 'customer@example.com')
            ->set('password', 'secret123')
            ->call('login')
            ->assertRedirect();

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_incorrect_credentials(): void
    {
        User::factory()->create([
            'email' => 'customer@example.com',
            'password' => Hash::make('secret123'),
        ]);

        Livewire::test(LoginPage::class)
            ->set('email', 'customer@example.com')
            ->set('password', 'wrong-password')
            ->call('login')
            ->assertHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
