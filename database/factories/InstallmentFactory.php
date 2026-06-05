<?php

namespace Database\Factories;

use App\Models\Installment;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstallmentFactory extends Factory
{
    protected $model = \App\Models\Installment::class;

    public function definition(): array
    {
        return [
            'installment_number' => 1,
            'amount' => 10000,
            'due_date' => now()->addMonths(1),
        ];
    }
}
