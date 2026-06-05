<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = $this->faker->randomElement(['M', 'F']);
        
        return [
            'first_name' => $this->faker->firstName($gender === 'M' ? 'male' : 'female'),
            'last_name' => $this->faker->lastName(),
            'gender' => $gender,
            'matricule' => 'MAT-' . $this->faker->unique()->numberBetween(100000, 999999),
            'birth_date' => $this->faker->dateTimeBetween('-18 years', '-3 years'),
            'birth_place' => $this->faker->city(),
            'nationality' => $this->faker->country(),
            'country' => $this->faker->country(),
            'address' => $this->faker->address(),
            'guardian_name' => $this->faker->name(),
            'guardian_phone' => $this->faker->phoneNumber(),
            'guardian_email' => $this->faker->safeEmail(),
            'guardian_relation' => $this->faker->randomElement(['Père', 'Mère', 'Oncle', 'Tante', 'Tuteur']),
            'guardian_profession' => $this->faker->jobTitle(),
            'birth_certificate_path' => $this->faker->boolean(80) ? 'placeholder/birth_cert.pdf' : null,
            'photo_path' => $this->faker->boolean(70) ? 'placeholder/photo.jpg' : null,
            'attestation_path' => $this->faker->boolean(50) ? 'placeholder/attestation.pdf' : null,
        ];
    }
}
