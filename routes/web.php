<?php

use App\Http\Controllers\InvoiceController;
use App\Livewire\AboutPage;
use App\Livewire\HowToOrderPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Import Livewire Components
use App\Livewire\HomePage;
use App\Livewire\CatalogPage;
use App\Livewire\ProductDetail;
use App\Livewire\CartPage;
use App\Livewire\CheckoutPage;
use App\Livewire\SuccessPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\ContactPage;
use App\Livewire\ReviewsPage;
use App\Models\Review;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// PUBLIC ROUTES 
Route::get('/', HomePage::class)->name('home');
Route::get('/products', CatalogPage::class)->name('products.index');
Route::get('/products/{slug}', ProductDetail::class)->name('products.show');
Route::get('/cart', CartPage::class)->name('cart');
Route::get('/about', AboutPage::class)->name('about');
Route::get('/contact', ContactPage::class)->name('contact');
Route::get('/reviews', ReviewsPage::class)->name('reviews');
Route::get('/how-to-order', HowToOrderPage::class)->name('how-to-order');

// GUEST ROUTES 
Route::middleware('guest')->group(function () {
    Route::get('/login', LoginPage::class)->name('login');
    Route::get('/register', RegisterPage::class)->name('register');
});

// AUTHENTICATED ROUTES 
Route::middleware('auth')->group(function () {
    // Belanja & Order
    Route::get('/checkout', CheckoutPage::class)->name('checkout');
    Route::get('/success/{order_id}', SuccessPage::class)->name('success');
    
    // Riwayat Pesanan
    Route::get('/my-orders', MyOrdersPage::class)->name('my-orders');
    Route::get('/my-orders/{order_id}', MyOrderDetailPage::class)->name('my-orders.show');

    Route::get('/invoice/{order_id}', [InvoiceController::class, 'download'])->name('invoice.download');

    // Logout 
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});