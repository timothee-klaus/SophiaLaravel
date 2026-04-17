<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'verification_code',
        'status',
        'verified_at',
        'approved_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'approved_at' => 'datetime',
    ];
}
