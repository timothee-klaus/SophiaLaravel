<?php

namespace App\Livewire;

use App\Mail\VerifyRegistrationEmail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DirectorRegistration extends Component
{
    public $step = 1;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $verificationCode;
    public $inputCode;

    protected $rules = [
        1 => [
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ],
        2 => [
            'inputCode' => 'required|string|size:6',
        ],
    ];

    public function submitRequest()
    {
        $this->validate($this->rules[1]);

        $this->verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        session(['director_signup' => [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'code' => $this->verificationCode,
        ]]);

        Mail::to($this->email)->send(new VerifyRegistrationEmail($this->name, $this->verificationCode));

        $this->step = 2;
    }

    public function verifyCode()
    {
        $this->validate($this->rules[2]);

        $signupData = session('director_signup');

        if ($this->inputCode === $signupData['code']) {
            $user = User::create([
                'name' => $signupData['name'],
                'email' => $signupData['email'],
                'password' => Hash::make($signupData['password']),
                'role' => 'director',
            ]);

            Auth::login($user);
            
            session()->forget('director_signup');

            return redirect()->route('dashboard');
        } else {
            $this->addError('inputCode', 'Le code de vérification est incorrect.');
        }
    }

    public function render()
    {
        return view('livewire.director-registration')->layout('components.layouts.guest');
    }
}
