<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Installment extends Model
{
    protected $fillable = [
        'tuition_fee_id',
        'installment_number',
        'amount',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'due_date' => 'date',
        ];
    }

    public function tuitionFee(): BelongsTo
    {
        return $this->belongsTo(TuitionFee::class);
    }
}
