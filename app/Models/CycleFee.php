<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CycleFee extends Model
{
    protected $fillable = [
        'academic_year_id',
        'cycle',
        'registration_fee',
        'miscellaneous_fee',
        'exam_miscellaneous_fee',
    ];

    protected function casts(): array
    {
        return [
            'registration_fee' => 'decimal:2',
            'miscellaneous_fee' => 'decimal:2',
            'exam_miscellaneous_fee' => 'decimal:2',
        ];
    }

    public function academicYear(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
