<?php

namespace Database\Factories;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        return [
            'user_id' => 1,
            'event' => $this->faker->randomElement(['create', 'update', 'delete', 'login']),
            'auditable_type' => $this->faker->randomElement(['App\Models\Student', 'App\Models\Payment', 'App\Models\Enrollment']),
            'auditable_id' => $this->faker->numberBetween(1, 100),
            'old_values' => null,
            'new_values' => json_encode(['field' => 'value']),
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
        ];
    }
}
