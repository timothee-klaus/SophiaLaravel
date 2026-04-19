<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\Auditable;

class Student extends Model
{
    use Auditable;
    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'matricule',
        'birth_date',
        'birth_place',
        'nationality',
        'country',
        'address',
        'guardian_name',
        'guardian_phone',
        'guardian_email',
        'guardian_relation',
        'guardian_profession',
        'birth_certificate_path',
        'photo_path',
        'attestation_path',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Determine if the student is new to the institution.
     * A student is considered new if they have no enrollments in previous academic years.
     */
    public function isNew(int $currentYearId): bool
    {
        return ! $this->enrollments()
            ->where('academic_year_id', '<', $currentYearId)
            ->exists();
    }
}
