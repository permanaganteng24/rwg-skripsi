<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class ContactPage extends Component
{
    #[Title('Contact Us - Rizqi Wood Gallery')]
    public $name;
    public $email;
    public $subject;
    public $message;

    // Rules Validation
    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'subject' => 'nullable|string',
        'message' => 'required|min:10',
    ];

    public function submitMessage()
    {
        $this->validate();
        $this->reset();

        // Send success alert
        $this->dispatch('alert', type: 'success', message: 'Thank you! Your message has been sent.');
    }

    public function render()
    {
        return view('livewire.contact-page');
    }
}