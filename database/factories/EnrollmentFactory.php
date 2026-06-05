<?php

namespace Database\Factories;

use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    protected $model = Enrollment::class;

    public function definition(): array
    {
        return [
            'has_complete_file' => $this->faker->boolean(90),
            'status' => 'active',
            'is_manually_unblocked' => false,
        ];
    }
}
