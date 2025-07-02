<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.home')]
class Login extends Component
{
     #[Validate('required|email')]
    public $email = '';
    
    #[Validate('required|min:6')]
    public $password = '';
    
    public $remember = false;
    
    public function login()
    {
        $this->validate();
        
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            return redirect()->intended('/dashboard');
        }
        
        $this->addError('email', 'Invalid credentials provided.');
    }
    public function render()
    {
        return view('livewire.login');
    }
}
