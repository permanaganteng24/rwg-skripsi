<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component
{
    public $total_count = 0;

    public function mount()
    {
        $this->total_count = CartManagement::getCartItemsCount();
    }

    #[On('cart-updated')]
    public function updateCartCount($total_count)
    {
        $this->total_count = CartManagement::getCartItemsCount();
    }

    public function render()
    {
        return view('livewire.navbar');
    }
}
