<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    protected $fillable = [
        'name',
        'cycle',
        'is_exam_class',
    ];

    protected function casts(): array
    {
        return [
            'is_exam_class' => 'boolean',
        ];
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function tuitionFees(): HasMany
    {
        return $this->hasMany(TuitionFee::class);
    }
}
