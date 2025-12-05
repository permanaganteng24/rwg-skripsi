<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class AboutPage extends Component
{
    #[Title('Tentang Kami - Rizqi Wood Gallery')]

    public function render()
    {
        return view('livewire.about-page');
    }
}