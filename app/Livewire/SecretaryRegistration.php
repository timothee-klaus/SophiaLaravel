<?php

namespace App\Livewire;

use App\Mail\AdminRegistrationNotification;
use App\Mail\VerifyRegistrationEmail;
use App\Models\RegistrationRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class SecretaryRegistration extends Component
{
    public $step = 1;
    public $name;
    public $email;
    public $verificationCode;
    public $inputCode;
    public $requestId;

    protected $rules = [
        1 => [
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email|unique:registration_requests,email',
        ],
        2 => [
            'inputCode' => 'required|string|size:6',
        ],
    ];

    public function submitRequest()
    {
        $this->validate($this->rules[1]);

        $this->verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $request = RegistrationRequest::updateOrCreate(
            ['email' => $this->email],
            [
                'name' => $this->name,
                'verification_code' => $this->verificationCode,
                'status' => 'pending'
            ]
        );

        $this->requestId = $request->id;

        Mail::to($this->email)->send(new VerifyRegistrationEmail($this->name, $this->verificationCode));

        $this->step = 2;
    }

    public function verifyCode()
    {
        $this->validate($this->rules[2]);

        $request = RegistrationRequest::find($this->requestId);

        if ($this->inputCode === $request->verification_code) {
            $request->update([
                'status' => 'verified',
                'verified_at' => now(),
                'verification_code' => null, // Clear code after verification
            ]);

            // Notify Admin
            $approvalUrl = URL::signedRoute('registration.approve', ['request' => $request->id]);
            
            // For now, we assume admin email is admin@sophia.com
            Mail::to('admin@sophia.com')->send(new AdminRegistrationNotification($request, $approvalUrl));

            $this->step = 3;
        } else {
            $this->addError('inputCode', 'Le code de vérification est incorrect.');
        }
    }

    public function render()
    {
        return view('livewire.secretary-registration')->layout('components.layouts.guest');
    }
}
