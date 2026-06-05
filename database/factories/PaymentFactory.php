<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'amount' => $this->faker->numberBetween(5000, 50000),
            'type' => $this->faker->randomElement(['registration', 'tuition', 'miscellaneous', 'exam']),
            'installment_number' => null,
            'transaction_id' => 'TX-' . Str::upper(Str::random(10)),
            'receipt_path' => null,
        ];
    }
}
