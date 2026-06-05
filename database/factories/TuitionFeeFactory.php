<?php

namespace Database\Factories;

use App\Models\TuitionFee;
use Illuminate\Database\Eloquent\Factories\Factory;

class TuitionFeeFactory extends Factory
{
    protected $model = TuitionFee::class;

    public function definition(): array
    {
        return [
            'total_amount' => $this->faker->numberBetween(50000, 150000),
            'registration_fee' => $this->faker->numberBetween(5000, 20000),
            'miscellaneous_fee' => $this->faker->numberBetween(0, 10000),
        ];
    }
}
