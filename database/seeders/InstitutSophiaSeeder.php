<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AcademicYear;
use App\Models\Level;
use App\Models\TuitionFee;
use App\Models\Installment;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Payment;

class InstitutSophiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Default Users
        User::firstOrCreate(['email' => 'admin@sophia.com'], [
            'name' => 'Admin Directeur',
            'password' => bcrypt('password'),
            'role' => 'director',
        ]);

        User::firstOrCreate(['email' => 'secretaire@sophia.com'], [
            'name' => 'Secrétaire',
            'password' => bcrypt('password'),
        ]);

        // Create Academic Year
        $academicYear = AcademicYear::firstOrCreate(['name' => '2025-2026'], [
            'is_current' => true,
            'is_closed' => false,
        ]);

        // Create Levels (Preschool, Primary, College, Lycee) examples
        $levelsData = [
            ['name' => 'Maternelle 1', 'cycle' => 'preschool', 'is_exam_class' => false],
            ['name' => 'CP', 'cycle' => 'primary', 'is_exam_class' => false],
            ['name' => '6ème', 'cycle' => 'college', 'is_exam_class' => false],
            ['name' => 'Terminale', 'cycle' => 'lycee', 'is_exam_class' => true],
        ];

        foreach ($levelsData as $ld) {
            $level = Level::firstOrCreate(['name' => $ld['name']], $ld);

            // Create Tuition Fees for this level
            $feeAmount = match ($ld['cycle']) {
                'preschool' => 50000,
                'primary' => 60000,
                'college' => 80000,
                'lycee' => 100000,
            };

            $regFee = match ($ld['cycle']) {
                'preschool', 'primary' => 3000,
                'college' => 5000,
                'lycee' => 10000,
            };

            $tuitionFee = TuitionFee::firstOrCreate([
                'level_id' => $level->id,
                'academic_year_id' => $academicYear->id,
            ], [
                'total_amount' => $feeAmount,
                'registration_fee' => $regFee,
                'miscellaneous_fee' => 0,
            ]);

            // Create installments
            Installment::firstOrCreate([
                'tuition_fee_id' => $tuitionFee->id,
                'installment_number' => 1
            ], [
                'amount' => $feeAmount * 0.4,
                'due_date' => '2025-10-31'
            ]);

            Installment::firstOrCreate([
                'tuition_fee_id' => $tuitionFee->id,
                'installment_number' => 2
            ], [
                'amount' => $feeAmount * 0.3,
                'due_date' => '2026-01-31'
            ]);

            Installment::firstOrCreate([
                'tuition_fee_id' => $tuitionFee->id,
                'installment_number' => 3
            ], [
                'amount' => $feeAmount * 0.3,
                'due_date' => '2026-04-30'
            ]);

            // Create a fake student for this level
            $student = Student::firstOrCreate(['matricule' => 'MAT-2026-00' . rand(100, 999)], [
                'first_name' => 'Student ' . rand(1, 100),
                'last_name' => 'Test',
                'gender' => rand(0, 1) ? 'M' : 'F',
                'birth_date' => '2015-05-15',
            ]);

            $enrollment = Enrollment::firstOrCreate([
                'student_id' => $student->id,
                'academic_year_id' => $academicYear->id,
            ], [
                'level_id' => $level->id,
            ]);

            // Add an initial registration fee payment
            Payment::firstOrCreate([
                'student_id' => $student->id,
                'academic_year_id' => $academicYear->id,
                'type' => 'registration',
            ], [
                'amount' => $regFee,
                'transaction_id' => 'TXN-REG-' . uniqid(),
            ]);

            // Add first installment payment
            Payment::firstOrCreate([
                'student_id' => $student->id,
                'academic_year_id' => $academicYear->id,
                'type' => 'tuition',
                'installment_number' => 1,
            ], [
                'amount' => $feeAmount * 0.4,
                'transaction_id' => 'TXN-TUI-' . uniqid(),
            ]);
        }
    }
}
