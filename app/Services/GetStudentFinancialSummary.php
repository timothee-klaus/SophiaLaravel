<?php

namespace App\Services;

use App\Models\Student;
use App\Models\TuitionFee;

class GetStudentFinancialSummary
{
    public function execute(Student $student, int $academicYearId): array
    {
        $enrollment = $student->enrollments()->where('academic_year_id', $academicYearId)->first();
        if (!$enrollment) {
            return [];
        }

        $totalPaid = $enrollment->getTotalPaid();

        $tuitionFee = TuitionFee::where('level_id', $enrollment->level_id)
            ->where('academic_year_id', $academicYearId)
            ->first();

        $totalRequired = $tuitionFee ? ((float) $tuitionFee->total_amount + (float) $tuitionFee->registration_fee + (float) $tuitionFee->miscellaneous_fee) : 0.0;

        $balanceRemaining = max(0, $totalRequired - $totalPaid);

        return [
            'id' => $student->id,
            'name' => trim($student->first_name . ' ' . $student->last_name),
            'cycle' => $enrollment->level->cycle,
            'total_paid' => $totalPaid,
            'balance_remaining' => $balanceRemaining,
            'is_eligible_for_exams' => $enrollment->isEligibleForExams(),
        ];
    }
}

