<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\Auditable;

class AcademicYear extends Model
{
    use HasFactory, Auditable;

    protected $guarded = [];
}
