<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TuitionFee extends Model
{
    protected $fillable = [
        'level_id',
        'academic_year_id',
        'total_amount',
        'registration_fee',
        'miscellaneous_fee',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'registration_fee' => 'decimal:2',
            'miscellaneous_fee' => 'decimal:2',
        ];
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }
}
