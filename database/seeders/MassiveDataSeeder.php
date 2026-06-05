<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Enrollment;
use App\Models\Level;
use App\Models\Payment;
use App\Models\RegistrationRequest;
use App\Models\Student;
use App\Models\TuitionFee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MassiveDataSeeder extends Seeder
{
    public function run(): void
    {
        $pastYear = AcademicYear::where('name', '2024-2025')->first();
        $currentYear = AcademicYear::where('name', '2025-2026')->first();
        $levels = Level::all();

        // 1. Generate 800 Students first
        $students = Student::factory(800)->create();

        // 2. Enroll students and generate payments
        foreach ($students as $index => $student) {
            // Some students were there last year
            $wasThereLastYear = $index < 600;
            
            if ($wasThereLastYear) {
                $levelLastYear = $levels->random();
                $this->enrollAndPay($student, $levelLastYear, $pastYear, true); // Past year fully paid
            }

            // Most students are here this year
            $isHereThisYear = $index < 750;
            if ($isHereThisYear) {
                $levelThisYear = $levels->random();
                // Randomize scenarios for current year
                $this->enrollAndPay($student, $levelThisYear, $currentYear, false);
            }
        }

        // 3. Generate 200 Registration Requests
        RegistrationRequest::factory()->count(200)->create();
    }

    private function enrollAndPay($student, $level, $year, $fullyPaid = false)
    {
        $status = 'active';
        if (!$fullyPaid && rand(0, 10) > 8) {
            $status = rand(0, 1) ? 'dropped_out' : 'suspended';
        }

        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'level_id' => $level->id,
            'academic_year_id' => $year->id,
            'status' => $status,
            'has_complete_file' => rand(0, 10) > 2,
        ]);

        $tuitionFee = TuitionFee::where('level_id', $level->id)
            ->where('academic_year_id', $year->id)
            ->first();

        if (!$tuitionFee) return;

        // Pay Registration
        Payment::create([
            'student_id' => $student->id,
            'academic_year_id' => $year->id,
            'amount' => $tuitionFee->registration_fee,
            'type' => 'registration',
        ]);

        // Pay Miscellaneous (sometimes)
        if ($fullyPaid || rand(0, 10) > 3) {
            Payment::create([
                'student_id' => $student->id,
                'academic_year_id' => $year->id,
                'amount' => $tuitionFee->miscellaneous_fee,
                'type' => 'miscellaneous',
            ]);
        }

        // Tuition Payments
        $installments = $tuitionFee->installments;
        foreach ($installments as $installment) {
            $shouldPay = $fullyPaid || rand(0, 10) > 4;
            
            if ($shouldPay) {
                Payment::create([
                    'student_id' => $student->id,
                    'academic_year_id' => $year->id,
                    'amount' => $installment->amount,
                    'type' => 'tuition',
                    'installment_number' => $installment->installment_number,
                ]);
            } else {
                // Partially pay some installments
                if (rand(0, 10) > 7) {
                    Payment::create([
                        'student_id' => $student->id,
                        'academic_year_id' => $year->id,
                        'amount' => $installment->amount / 2,
                        'type' => 'tuition',
                        'installment_number' => $installment->installment_number,
                    ]);
                }
                break; // Stop at first unpaid if not fully paid (simplified simulation)
            }
        }

        // Exam fees for exam classes
        if ($level->is_exam_class) {
            if ($fullyPaid || rand(0, 10) > 5) {
                Payment::create([
                    'student_id' => $student->id,
                    'academic_year_id' => $year->id,
                    'amount' => 5000, // Hardcoded exam fee for simulation
                    'type' => 'exam',
                ]);
            }
        }
    }
}
