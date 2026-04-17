<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\TuitionFee;
use App\Models\Installment;
use App\Models\Student;
use Illuminate\Http\JsonResponse;

class DirectorController extends Controller
{
    public function dashboard(): JsonResponse
    {
        $currentYearId = 1; // Simplification pour l'année courante

        // 1. Le montant total perçu pour l'année en cours
        $totalCollected = (float) Payment::where('academic_year_id', $currentYearId)->sum('amount');

        // 2. La liste des élèves en retard de paiement (impayés sur la tranche actuelle)
        $today = now();
        $pastDueInstallments = Installment::where('due_date', '<', $today)
            ->whereHas('tuitionFee', function ($query) use ($currentYearId) {
                $query->where('academic_year_id', $currentYearId);
            })->get();

        $lateStudents = [];

        // C'est une extraction simplifiée, une jointure plus complexe serait plus performante
        $students = Student::whereHas('enrollments', function($q) use($currentYearId) {
            $q->where('academic_year_id', $currentYearId);
        })->get();

        foreach ($students as $student) {
            $enrollment = $student->enrollments()->where('academic_year_id', $currentYearId)->first();
            if (!$enrollment) continue;

            $tuitionFee = TuitionFee::where('level_id', $enrollment->level_id)
                ->where('academic_year_id', $currentYearId)->first();

            if (!$tuitionFee) continue;

            foreach ($pastDueInstallments->where('tuition_fee_id', $tuitionFee->id) as $installment) {
                $paid = Payment::where('student_id', $student->id)
                    ->where('academic_year_id', $currentYearId)
                    ->where('type', 'tuition')
                    ->where('installment_number', $installment->installment_number)
                    ->sum('amount');

                if ((float) $paid < (float) $installment->amount) {
                    $lateStudents[] = [
                        'id' => $student->id,
                        'name' => trim($student->first_name . ' ' . $student->last_name),
                        'missing_installment' => $installment->installment_number,
                        'due_amount' => (float) $installment->amount - (float) $paid
                    ];
                }
            }
        }

        // 3. Le taux de recouvrement par cycle (Primaire, Collège, Lycée)
        $fees = TuitionFee::with('level')->where('academic_year_id', $currentYearId)->get();
        $expectedByCycle = [
            'preschool' => 0.0,
            'primary' => 0.0,
            'college' => 0.0,
            'lycee' => 0.0,
        ];
        $collectedByCycle = [
            'preschool' => 0.0,
            'primary' => 0.0,
            'college' => 0.0,
            'lycee' => 0.0,
        ];

        // Calcul des attendus globaux par cycle
        foreach ($fees as $fee) {
            $studentsCount = \App\Models\Enrollment::where('level_id', $fee->level_id)
                ->where('academic_year_id', $currentYearId)->count();

            $expectedByCycle[$fee->level->cycle] += ((float) $fee->total_amount + (float) $fee->registration_fee + (float) $fee->miscellaneous_fee) * $studentsCount;
        }

        // Calcul des paiements collectés par cycle
        $payments = Payment::where('academic_year_id', $currentYearId)->get();
        foreach ($payments as $payment) {
            $enrollment = \App\Models\Enrollment::where('student_id', $payment->student_id)
                ->where('academic_year_id', $currentYearId)->first();
            if ($enrollment) {
                $collectedByCycle[$enrollment->level->cycle] += (float) $payment->amount;
            }
        }

        $recoveryRateByCycle = [];
        foreach ($expectedByCycle as $cycle => $expected) {
            $rate = $expected > 0 ? round(($collectedByCycle[$cycle] / $expected) * 100, 2) : 0;
            $recoveryRateByCycle[$cycle] = $rate . ' %';
        }

        return response()->json([
            'total_collected' => $totalCollected,
            'late_students' => $lateStudents,
            'recovery_rate_by_cycle' => $recoveryRateByCycle,
        ]);
    }
}

