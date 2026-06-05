<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\CycleFee;
use App\Models\Installment;
use App\Models\Level;
use App\Models\TuitionFee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class StructureSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Academic Years
        $pastYear = AcademicYear::create([
            'name' => '2024-2025',
            'start_date' => '2024-09-01',
            'end_date' => '2025-06-30',
            'is_current' => false,
            'is_closed' => true,
        ]);

        $currentYear = AcademicYear::create([
            'name' => '2025-2026',
            'start_date' => '2025-09-01',
            'end_date' => '2026-06-30',
            'is_current' => true,
            'is_closed' => false,
        ]);

        $futureYear = AcademicYear::create([
            'name' => '2026-2027',
            'start_date' => '2026-09-01',
            'end_date' => '2027-06-30',
            'is_current' => false,
            'is_closed' => false,
        ]);

        // 2. Cycle Fees
        $cycles = [
            'preschool' => ['reg' => 5000, 'misc' => 2000],
            'primary' => ['reg' => 5000, 'misc' => 3000],
            'college' => ['reg' => 10000, 'misc' => 5000],
            'lycee' => ['reg' => 15000, 'misc' => 7000],
        ];

        foreach ([$pastYear, $currentYear, $futureYear] as $year) {
            foreach ($cycles as $cycle => $fees) {
                CycleFee::create([
                    'academic_year_id' => $year->id,
                    'cycle' => $cycle,
                    'registration_fee' => $fees['reg'],
                    'miscellaneous_fee' => $fees['misc'],
                ]);
            }
        }

        // 3. Levels and Tuition Fees
        $levelsData = [
            // Maternelle
            ['name' => 'Petite Section', 'cycle' => 'preschool', 'is_exam' => false, 'fee' => 45000],
            ['name' => 'Moyenne Section', 'cycle' => 'preschool', 'is_exam' => false, 'fee' => 45000],
            ['name' => 'Grande Section', 'cycle' => 'preschool', 'is_exam' => false, 'fee' => 50000],
            // Primaire
            ['name' => 'CP', 'cycle' => 'primary', 'is_exam' => false, 'fee' => 55000],
            ['name' => 'CE1', 'cycle' => 'primary', 'is_exam' => false, 'fee' => 55000],
            ['name' => 'CE2', 'cycle' => 'primary', 'is_exam' => false, 'fee' => 60000],
            ['name' => 'CM1', 'cycle' => 'primary', 'is_exam' => false, 'fee' => 60000],
            ['name' => 'CM2', 'cycle' => 'primary', 'is_exam' => true, 'fee' => 65000],
            // Collège
            ['name' => '6ème', 'cycle' => 'college', 'is_exam' => false, 'fee' => 85000],
            ['name' => '5ème', 'cycle' => 'college', 'is_exam' => false, 'fee' => 85000],
            ['name' => '4ème', 'cycle' => 'college', 'is_exam' => false, 'fee' => 90000],
            ['name' => '3ème', 'cycle' => 'college', 'is_exam' => true, 'fee' => 95000],
            // Lycée
            ['name' => 'Seconde', 'cycle' => 'lycee', 'is_exam' => false, 'fee' => 110000],
            ['name' => 'Première', 'cycle' => 'lycee', 'is_exam' => false, 'fee' => 110000],
            ['name' => 'Terminale', 'cycle' => 'lycee', 'is_exam' => true, 'fee' => 125000],
        ];

        foreach ($levelsData as $ld) {
            $level = Level::create([
                'name' => $ld['name'],
                'cycle' => $ld['cycle'],
                'is_exam_class' => $ld['is_exam'],
            ]);

            foreach ([$pastYear, $currentYear, $futureYear] as $year) {
                $tuitionFee = TuitionFee::create([
                    'level_id' => $level->id,
                    'academic_year_id' => $year->id,
                    'total_amount' => $ld['fee'],
                    'registration_fee' => $cycles[$ld['cycle']]['reg'],
                    'miscellaneous_fee' => $cycles[$ld['cycle']]['misc'],
                ]);

                // 3 Installments
                $t1 = $ld['fee'] * 0.4;
                $t2 = $ld['fee'] * 0.3;
                $t3 = $ld['fee'] * 0.3;

                $startYear = explode('-', $year->name)[0];

                Installment::create([
                    'tuition_fee_id' => $tuitionFee->id,
                    'installment_number' => 1,
                    'amount' => $t1,
                    'due_date' => Carbon::parse("$startYear-10-31"),
                ]);

                Installment::create([
                    'tuition_fee_id' => $tuitionFee->id,
                    'installment_number' => 2,
                    'amount' => $t2,
                    'due_date' => Carbon::parse(($startYear + 1) . "-01-31"),
                ]);

                Installment::create([
                    'tuition_fee_id' => $tuitionFee->id,
                    'installment_number' => 3,
                    'amount' => $t3,
                    'due_date' => Carbon::parse(($startYear + 1) . "-04-30"),
                ]);
            }
        }
    }
}
