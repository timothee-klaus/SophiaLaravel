<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Enrollment;
use App\Models\TuitionFee;
use App\Models\Payment;

class RegisterStudentAction
{
    /**
     * Enregistre un nouvel élève et son inscription pour l'année.
     */
    public function execute(array $studentData, int $levelId, int $academicYearId): Student
    {
        // Génération du matricule : MAT-ANNEE-XXX
        // Ex: MAT-2025-006 (calcul basique basé sur le count total pour l'exemple)
        $yearForMatricule = date('Y');
        $totalStudents = Student::count();
        $matricule = sprintf('MAT-%s-%03d', $yearForMatricule, $totalStudents + 1);

        $student = Student::create([
            'first_name' => $studentData['first_name'],
            'last_name'  => $studentData['last_name'],
            'gender'     => $studentData['gender'],
            'birth_date' => $studentData['birth_date'],
            'birth_place' => $studentData['birth_place'] ?? null,
            'nationality' => $studentData['nationality'] ?? null,
            'address' => $studentData['address'] ?? null,
            'guardian_name' => $studentData['guardian_name'] ?? null,
            'guardian_phone' => $studentData['guardian_phone'] ?? null,
            'guardian_email' => $studentData['guardian_email'] ?? null,
            'guardian_relation' => $studentData['guardian_relation'] ?? null,
            'guardian_profession' => $studentData['guardian_profession'] ?? null,
            'matricule'  => $matricule,
            'birth_certificate_path' => $studentData['birth_certificate_path'] ?? null,
            'photo_path' => $studentData['photo_path'] ?? null,
            'attestation_path' => $studentData['attestation_path'] ?? null,
        ]);

        Enrollment::create([
            'student_id'       => $student->id,
            'level_id'         => $levelId,
            'academic_year_id' => $academicYearId,
        ]);

        // Enregistrement automatique des frais d'inscription
        $fee = TuitionFee::where('level_id', $levelId)
            ->where('academic_year_id', $academicYearId)
            ->first();

        Payment::create([
            'student_id' => $student->id,
            'academic_year_id' => $academicYearId,
            'amount' => $fee ? $fee->registration_fee : 0,
            'type' => 'registration',
        ]);

        return $student;
    }
}
