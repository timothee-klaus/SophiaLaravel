<?php

namespace Database\Factories;

use App\Models\CycleFee;
use Illuminate\Database\Eloquent\Factories\Factory;

class CycleFeeFactory extends Factory
{
    protected $model = CycleFee::class;

    public function definition(): array
    {
        return [
            'cycle' => 'primary',
            'registration_fee' => 5000,
            'miscellaneous_fee' => 2000,
        ];
    }
}
