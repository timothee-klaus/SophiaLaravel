<?php
namespace App\Livewire;
use App\Models\AcademicYear;
use App\Models\Enrollment;
use App\Models\Installment;
use App\Models\Payment;
use App\Models\Student;
use App\Models\TuitionFee;
use Carbon\Carbon;
use Livewire\Component;
class SecretaryDashboard extends Component
{
    public $totalCollected = 0;
    public $filterCycle = '';
    public $labels = [];
    public $data = [];
    public $levelLabels = [];
    public $levelData = [];

    public function mount()
    {
        $activeYear = AcademicYear::where('is_current', true)->first() ?? AcademicYear::first();
        if ($activeYear) {
            $this->totalCollected = Payment::where('academic_year_id', $activeYear->id)
                ->sum('amount') ?? 0;
        }
    }

    public function render()
    {
        $activeYear = AcademicYear::where('is_current', true)->first() ?? AcademicYear::first();

        // Distribution par type de paiement
        $this->labels = ['Inscription', 'Scolarité', 'Divers'];
        $this->data = [
            Payment::where('type', 'registration')->where('academic_year_id', $activeYear?->id)->sum('amount') ?? 0,
            Payment::where('type', 'tuition')->where('academic_year_id', $activeYear?->id)->sum('amount') ?? 0,
            Payment::where('type', 'miscellaneous')->where('academic_year_id', $activeYear?->id)->sum('amount') ?? 0,
        ];

        $yearlyRevenue = 0;
        $yearlyEnrollments = 0;
        $incompleteFiles = 0;
        $lateStudentsCount = 0;

        if ($activeYear) {
            $yearlyRevenue = Payment::where('academic_year_id', $activeYear->id)
                ->sum('amount');
            $yearlyEnrollments = Enrollment::where('academic_year_id', $activeYear->id)
                ->count();

            $incompleteFiles = Enrollment::where('academic_year_id', $activeYear->id)
                ->whereHas('student', function($q) {
                    $q->whereNull('birth_certificate_path')
                      ->orWhereNull('photo_path')
                      ->orWhereNull('attestation_path');
                })
                ->count();

            // Retards critiques - OPTIMIZED
            $pastDueInstallments = Installment::where('due_date', '<', now())
                ->whereHas('tuitionFee', function ($q) use ($activeYear) {
                    $q->where('academic_year_id', $activeYear->id);
                })->get();

            if ($pastDueInstallments->isNotEmpty()) {
                $enrollments = Enrollment::where('academic_year_id', $activeYear->id)->get();
                $tuitionFees = TuitionFee::where('academic_year_id', $activeYear->id)->get()->keyBy('level_id');
                
                $paymentsByStudentAndInstallment = Payment::where('academic_year_id', $activeYear->id)
                    ->where('type', 'tuition')
                    ->selectRaw('student_id, installment_number, sum(amount) as total')
                    ->groupBy('student_id', 'installment_number')
                    ->get()
                    ->groupBy('student_id');

                foreach ($enrollments as $enrollment) {
                    $tf = $tuitionFees->get($enrollment->level_id);
                    if (!$tf) continue;
                    
                    $studentPayments = $paymentsByStudentAndInstallment->get($enrollment->student_id);
                    $isLate = false;
                    
                    foreach ($pastDueInstallments->where('tuition_fee_id', $tf->id) as $installment) {
                        $paid = 0;
                        if ($studentPayments) {
                            $sp = $studentPayments->firstWhere('installment_number', $installment->installment_number);
                            if ($sp) $paid = $sp->total;
                        }
                        
                        if ((float) $paid < (float) $installment->amount) {
                            $isLate = true;
                            break;
                        }
                    }
                    if ($isLate) {
                        $lateStudentsCount++;
                    }
                }
            }
        }

        $this->levelLabels = [];
        $this->levelData = [];

        if ($activeYear) {
            $query = \App\Models\Enrollment::where('enrollments.academic_year_id', $activeYear->id)
                ->join('levels', 'enrollments.level_id', '=', 'levels.id');
            
            if ($this->filterCycle) {
                $query->where('levels.cycle', $this->filterCycle);
            }

            $enrollmentsByLevel = $query->selectRaw('levels.name, count(*) as count')
                ->groupBy('levels.name')
                ->get();

            $this->levelLabels = $enrollmentsByLevel->pluck('name')->toArray();
            $this->levelData = $enrollmentsByLevel->pluck('count')->toArray();
        }

        $recentPayments = Payment::with(['student', 'academicYear'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        return view('livewire.secretary-dashboard', [
            'yearlyRevenue' => $yearlyRevenue,
            'yearlyEnrollments' => $yearlyEnrollments,
            'incompleteFiles' => $incompleteFiles,
            'lateStudentsCount' => $lateStudentsCount,
            'recentPayments' => $recentPayments,
        ]);
    }
}
