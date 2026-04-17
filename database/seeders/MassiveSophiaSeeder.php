<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AcademicYear;
use App\Models\Level;
use App\Models\TuitionFee;
use App\Models\Installment;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MassiveSophiaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Année Académique
        $academicYear = AcademicYear::firstOrCreate(['name' => '2025-2026'], [
            'is_current' => true,
            'is_closed' => false,
        ]);

        // 2. Définition de tous les niveaux (Maternelle à Terminale)
        $levelsData = [
            // Maternelle
            ['name' => 'Petite Section', 'cycle' => 'preschool', 'is_exam' => false, 'fee' => 45000, 'reg' => 5000],
            ['name' => 'Moyenne Section', 'cycle' => 'preschool', 'is_exam' => false, 'fee' => 45000, 'reg' => 5000],
            ['name' => 'Grande Section', 'cycle' => 'preschool', 'is_exam' => false, 'fee' => 50000, 'reg' => 5000],
            // Primaire
            ['name' => 'CP', 'cycle' => 'primary', 'is_exam' => false, 'fee' => 55000, 'reg' => 5000],
            ['name' => 'CE1', 'cycle' => 'primary', 'is_exam' => false, 'fee' => 55000, 'reg' => 5000],
            ['name' => 'CE2', 'cycle' => 'primary', 'is_exam' => false, 'fee' => 60000, 'reg' => 5000],
            ['name' => 'CM1', 'cycle' => 'primary', 'is_exam' => false, 'fee' => 60000, 'reg' => 5000],
            ['name' => 'CM2', 'cycle' => 'primary', 'is_exam' => true, 'fee' => 65000, 'reg' => 7500],
            // Collège
            ['name' => '6ème', 'cycle' => 'college', 'is_exam' => false, 'fee' => 85000, 'reg' => 10000],
            ['name' => '5ème', 'cycle' => 'college', 'is_exam' => false, 'fee' => 85000, 'reg' => 10000],
            ['name' => '4ème', 'cycle' => 'college', 'is_exam' => false, 'fee' => 90000, 'reg' => 10000],
            ['name' => '3ème', 'cycle' => 'college', 'is_exam' => true, 'fee' => 95000, 'reg' => 15000],
            // Lycée
            ['name' => 'Seconde', 'cycle' => 'lycee', 'is_exam' => false, 'fee' => 110000, 'reg' => 15000],
            ['name' => 'Première', 'cycle' => 'lycee', 'is_exam' => false, 'fee' => 110000, 'reg' => 15000],
            ['name' => 'Terminale', 'cycle' => 'lycee', 'is_exam' => true, 'fee' => 125000, 'reg' => 20000],
        ];

        foreach ($levelsData as $ld) {
            $level = Level::firstOrCreate(['name' => $ld['name']], [
                'cycle' => $ld['cycle'],
                'is_exam_class' => $ld['is_exam'],
            ]);

            $tuitionFee = TuitionFee::firstOrCreate([
                'level_id' => $level->id,
                'academic_year_id' => $academicYear->id,
            ], [
                'total_amount' => $ld['fee'],
                'registration_fee' => $ld['reg'],
                'miscellaneous_fee' => 0,
            ]);

            // Tranches (3 tranches : 40%, 30%, 30%)
            $tranche1 = $ld['fee'] * 0.4;
            $tranche2 = $ld['fee'] * 0.3;
            $tranche3 = $ld['fee'] * 0.3;

            Installment::updateOrCreate(['tuition_fee_id' => $tuitionFee->id, 'installment_number' => 1], ['amount' => $tranche1, 'due_date' => Carbon::parse('2025-10-30')]);
            Installment::updateOrCreate(['tuition_fee_id' => $tuitionFee->id, 'installment_number' => 2], ['amount' => $tranche2, 'due_date' => Carbon::parse('2026-01-30')]);
            Installment::updateOrCreate(['tuition_fee_id' => $tuitionFee->id, 'installment_number' => 3], ['amount' => $tranche3, 'due_date' => Carbon::parse('2026-04-30')]);

            // Générer un effectif réaliste entre 20 et 70 élèves par classe
            $studentCount = rand(20, 70);
            for ($i = 1; $i <= $studentCount; $i++) {
                $gender = rand(0, 1) ? 'M' : 'F';
                $firstName = fake()->firstName($gender == 'M' ? 'male' : 'female');
                $lastName = fake()->lastName;
                $matricule = 'MAT-' . rand(2000, 2026) . '-' . Str::random(4);

                $student = Student::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'gender' => $gender,
                    'matricule' => $matricule,
                    'birth_date' => Carbon::now()->subYears(rand(4, 18)),
                ]);

                $enrollment = Enrollment::create([
                    'student_id' => $student->id,
                    'academic_year_id' => $academicYear->id,
                    'level_id' => $level->id,
                    'status' => 'active',
                ]);

                // Simulation des paiements
                // Tous payent l'inscription
                Payment::create([
                    'student_id' => $student->id,
                    'academic_year_id' => $academicYear->id,
                    'amount' => $ld['reg'],
                    'type' => 'registration',
                    'transaction_id' => 'REG-' . Str::upper(Str::random(8)),
                ]);

                $scenario = rand(1, 4);
                if ($scenario >= 2) {
                    // Paye Tranche 1
                    Payment::create([
                        'student_id' => $student->id,
                        'academic_year_id' => $academicYear->id,
                        'amount' => $tranche1,
                        'type' => 'tuition',
                        'installment_number' => 1,
                        'transaction_id' => 'TUI1-' . Str::upper(Str::random(8)),
                    ]);
                }
                
                if ($scenario >= 3) {
                    // Paye Tranche 2
                    Payment::create([
                        'student_id' => $student->id,
                        'academic_year_id' => $academicYear->id,
                        'amount' => $tranche2,
                        'type' => 'tuition',
                        'installment_number' => 2,
                        'transaction_id' => 'TUI2-' . Str::upper(Str::random(8)),
                    ]);
                }

                if ($scenario == 4) {
                    // Paye Tranche 3 (Totalité)
                    Payment::create([
                        'student_id' => $student->id,
                        'academic_year_id' => $academicYear->id,
                        'amount' => $tranche3,
                        'type' => 'tuition',
                        'installment_number' => 3,
                        'transaction_id' => 'TUI3-' . Str::upper(Str::random(8)),
                    ]);
                }
            }
        }
    }
}
