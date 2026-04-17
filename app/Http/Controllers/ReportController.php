<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\AcademicYear;
use App\Models\Enrollment;
use App\Models\Level;
use App\Models\TuitionFee;

class ReportController extends Controller
{
    public function previewPayments(Request $request)
    {
        set_time_limit(300);
        ini_set('memory_limit', '1024M');

        $startDate = $request->query('start');
        $endDate = $request->query('end');
        $levelId = $request->query('level');
        $academicYearId = $request->query('year');
        $action = $request->query('action', 'stream');

        if (!$academicYearId) {
            abort(400, "Année académique requise.");
        }

        $payments = Payment::query()->with(['student.enrollments.level', 'academicYear'])
            ->where('academic_year_id', $academicYearId);

        if ($startDate) {
            $payments->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $payments->whereDate('created_at', '<=', $endDate);
        }
        if ($levelId) {
            $payments->whereHas('student.enrollments', function ($q) use ($levelId, $academicYearId) {
                $q->where('level_id', $levelId)
                  ->where('academic_year_id', $academicYearId);
            });
        }

        $pdf = Pdf::loadView('exports.payments-pdf', [
            'payments' => $payments->orderBy('created_at', 'desc')->get(),
            'academicYear' => AcademicYear::find($academicYearId),
            'level' => $levelId ? Level::find($levelId) : null,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        $fileName = 'Rapport_Paiements_' . date('Ymd_Hi') . '.pdf';
        return $action === 'download' ? $pdf->download($fileName) : $pdf->stream($fileName);
    }

    public function previewBalances(Request $request)
    {
        set_time_limit(300);
        ini_set('memory_limit', '512M');

        $levelId = $request->query('level');
        $academicYearId = $request->query('year');
        $action = $request->query('action', 'stream');

        if (!$academicYearId) {
            abort(400, "Année académique requise.");
        }

        $query = Enrollment::with(['student', 'level'])
            ->where('academic_year_id', $academicYearId);

        if ($levelId) {
            $query->where('level_id', $levelId);
        }

        $enrollments = $query->get();
        
        $tuitionFees = TuitionFee::where('academic_year_id', $academicYearId)->get()->keyBy('level_id');
        $studentIds = $enrollments->pluck('student_id');
        
        $paymentsSumByStudent = Payment::whereIn('student_id', $studentIds)
            ->where('academic_year_id', $academicYearId)
            ->selectRaw('student_id, sum(amount) as total')
            ->groupBy('student_id')
            ->pluck('total', 'student_id');

        $enrollments->transform(function($enrollment) use ($tuitionFees, $paymentsSumByStudent) {
            $totalPaid = $paymentsSumByStudent->get($enrollment->student_id, 0);
            $tuitionFee = $tuitionFees->get($enrollment->level_id);
                
            $totalRequired = $tuitionFee ? ($tuitionFee->total_amount + $tuitionFee->registration_fee + $tuitionFee->miscellaneous_fee) : 0;
            
            $enrollment->balance = max(0, $totalRequired - $totalPaid);
            $enrollment->total_paid = $totalPaid;
            $enrollment->total_required = $totalRequired;
            
            return $enrollment;
        });

        $pdf = Pdf::loadView('exports.balances-pdf', [
            'enrollments' => $enrollments,
            'academicYear' => AcademicYear::find($academicYearId),
            'level' => $levelId ? Level::find($levelId) : null
        ]);

        $fileName = 'Situation_Financiere_' . date('Ymd_Hi') . '.pdf';
        return $action === 'download' ? $pdf->download($fileName) : $pdf->stream($fileName);
    }
}
