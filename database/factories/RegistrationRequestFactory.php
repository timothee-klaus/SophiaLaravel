<?php

namespace Database\Factories;

use App\Models\RegistrationRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationRequestFactory extends Factory
{
    protected $model = RegistrationRequest::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'verification_code' => null,
            'status' => $this->faker->randomElement(['pending', 'verified', 'approved', 'rejected']),
            'verified_at' => $this->faker->boolean(70) ? now() : null,
            'approved_at' => $this->faker->boolean(40) ? now() : null,
        ];
    }
}
