<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcademicYearFactory extends Factory
{
    protected $model = AcademicYear::class;

    public function definition(): array
    {
        $year = $this->faker->unique()->numberBetween(2020, 2030);
        return [
            'name' => $year . '-' . ($year + 1),
            'start_date' => "$year-09-01",
            'end_date' => ($year + 1) . "-06-30",
            'is_current' => false,
            'is_closed' => false,
        ];
    }

    public function current(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_current' => true,
        ]);
    }
}
