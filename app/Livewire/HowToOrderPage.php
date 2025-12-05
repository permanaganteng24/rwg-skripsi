<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class HowToOrderPage extends Component
{
    #[Title('How to Order - Rizqi Wood Gallery')]

    public function render()
    {
        return view('livewire.how-to-order-page');
    }
}
