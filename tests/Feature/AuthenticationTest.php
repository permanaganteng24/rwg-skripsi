<?php

namespace Tests\Feature;

use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_valid_data(): void
    {
        Livewire::test(RegisterPage::class)
            ->set('name', 'Rizqi Customer')
            ->set('email', 'customer@example.com')
            ->set('password', 'password')
            ->call('register')
            ->assertRedirect('/checkout');

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'customer@example.com',
            'role' => 'customer',
        ]);
    }

    public function test_register_validates_required_unique_email_and_password_length(): void
    {
        $this->customerUser(['email' => 'taken@example.com']);

        Livewire::test(RegisterPage::class)
            ->set('name', '')
            ->set('email', 'taken@example.com')
            ->set('password', '123')
            ->call('register')
            ->assertHasErrors([
                'name' => 'required',
                'email' => 'unique',
                'password' => 'min',
            ]);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = $this->customerUser([
            'email' => 'login@example.com',
            'password' => Hash::make('password'),
        ]);

        Livewire::test(LoginPage::class)
            ->set('email', 'login@example.com')
            ->set('password', 'password')
            ->call('login')
            ->assertRedirect();

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_rejects_invalid_credentials_and_invalid_email_format(): void
    {
        Livewire::test(LoginPage::class)
            ->set('email', 'not-an-email')
            ->set('password', '')
            ->call('login')
            ->assertHasErrors([
                'email' => 'email',
                'password' => 'required',
            ]);

        $this->customerUser([
            'email' => 'login@example.com',
            'password' => Hash::make('password'),
        ]);

        Livewire::test(LoginPage::class)
            ->set('email', 'login@example.com')
            ->set('password', 'wrong-password')
            ->call('login')
            ->assertHasErrors(['email']);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $this->actingAs($this->customerUser());

        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }
}
