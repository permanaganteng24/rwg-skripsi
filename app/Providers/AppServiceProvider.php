<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use App\Models\CartItem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    Event::listen(Login::class, function ($event) {
        // Get cart dari cookie (milik guest)
        $cookieCart = json_decode(Cookie::get('cart_items'), true);
        
        if ($cookieCart && is_array($cookieCart)) {
            foreach ($cookieCart as $item) {
                CartItem::updateOrCreate(
                    [
                        'user_id' => $event->user->id,
                        'product_id' => $item['product_id']
                    ],
                    [
                        'quantity' => \Illuminate\Support\Facades\DB::raw('quantity + ' . $item['quantity'])
                    ]
                );
            }
            
            // Hapus cookie setelah dipindah ke DB
            Cookie::queue(Cookie::forget('cart_items'));
        }
    });
}
}
