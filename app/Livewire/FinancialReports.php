<?php
namespace App\Livewire;
use App\Exports\PaymentsExport;
use App\Exports\StudentsBalanceExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AcademicYear;
use App\Models\Level;
use App\Models\Payment;
use App\Models\Enrollment;
use App\Models\TuitionFee;
use Livewire\Component;
use Livewire\WithPagination;

class FinancialReports extends Component
{
    use WithPagination;
    public $activeTab = 'payments';
    // Filters for Payments
    public $startDate;
    public $endDate;
    public $paymentLevelId = '';
    public $paymentAcademicYearId;
    // Filters for Balances
    public $balanceLevelId = '';
    public $balanceAcademicYearId;
    public $levels = [];
    public $academicYears = [];
    public $totalInscriptions = 0;
    public $totalTuition = 0;
    public $totalMiscellaneous = 0;
    public $totalRevenue = 0;
    public function mount()
    {
        $this->levels = Level::orderBy('cycle')->get();
        // Assuming label field existed previously, or name? Migration shows name, updating to name to prevent error
        $this->academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $activeYear = AcademicYear::where('is_current', true)->first() ?? AcademicYear::first();
        if ($activeYear) {
            $this->paymentAcademicYearId = $activeYear->id;
            $this->balanceAcademicYearId = $activeYear->id;
        }
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->loadSummary();
    }
    public function exportPayments($format = 'xlsx')
    {
        $fileName = 'Paiements_' . now()->format('Ymd_Hi') . '.' . $format;
        $export = new PaymentsExport(
            $this->startDate,
            $this->endDate,
            $this->paymentLevelId ?: null,
            $this->paymentAcademicYearId ?: null
        );

        if ($format === 'pdf') {
            $payments = Payment::query()->with(['student', 'academicYear'])
                ->where('academic_year_id', $this->paymentAcademicYearId);

            if ($this->startDate) {
                $payments->whereDate('created_at', '>=', $this->startDate);
            }
            if ($this->endDate) {
                $payments->whereDate('created_at', '<=', $this->endDate);
            }
            if ($this->paymentLevelId) {
                $payments->whereHas('student.enrollments', function ($q) {
                    $q->where('level_id', $this->paymentLevelId)
                      ->where('academic_year_id', $this->paymentAcademicYearId);
                });
            }

            $pdf = Pdf::loadView('exports.payments-pdf', [
                'payments' => $payments->orderBy('created_at', 'desc')->get(),
                'academicYear' => AcademicYear::find($this->paymentAcademicYearId),
                'level' => $this->paymentLevelId ? Level::find($this->paymentLevelId) : null,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate
            ]);

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $fileName);
        }

        return $export->download($fileName);
    }
    public function exportBalances($format = 'xlsx')
    {
        $fileName = 'Soldes_Eleves_' . now()->format('Ymd_Hi') . '.' . $format;
        $export = new StudentsBalanceExport(
            $this->balanceLevelId ?: null,
            $this->balanceAcademicYearId ?: null
        );

        if ($format === 'pdf') {
            $query = Enrollment::with(['student', 'level'])
                ->where('academic_year_id', $this->balanceAcademicYearId);

            if ($this->balanceLevelId) {
                $query->where('level_id', $this->balanceLevelId);
            }

            $enrollments = $query->get()->transform(function($enrollment) {
                $totalPaid = Payment::where('student_id', $enrollment->student_id)
                    ->where('academic_year_id', $this->balanceAcademicYearId)
                    ->sum('amount');
                    
                $tuitionFee = TuitionFee::where('level_id', $enrollment->level_id)
                    ->where('academic_year_id', $this->balanceAcademicYearId)->first();
                    
                $totalRequired = $tuitionFee ? ($tuitionFee->total_amount + $tuitionFee->registration_fee + $tuitionFee->miscellaneous_fee) : 0;
                $balance = max(0, $totalRequired - $totalPaid);
                
                $enrollment->balance = $balance;
                $enrollment->total_paid = $totalPaid;
                $enrollment->total_required = $totalRequired;
                
                return $enrollment;
            });

            $pdf = Pdf::loadView('exports.balances-pdf', [
                'enrollments' => $enrollments,
                'academicYear' => AcademicYear::find($this->balanceAcademicYearId),
                'level' => $this->balanceLevelId ? Level::find($this->balanceLevelId) : null
            ]);

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $fileName);
        }

        return $export->download($fileName);
    }
    public function loadSummary()
    {
        $activeYear = AcademicYear::where('is_current', true)->first() ?? AcademicYear::first();

        $this->totalInscriptions = Payment::where('academic_year_id', $activeYear?->id)
            ->where('type', 'registration')
            ->sum('amount') ?? 0;

        $this->totalTuition = Payment::where('academic_year_id', $activeYear?->id)
            ->where('type', 'tuition')
            ->sum('amount') ?? 0;

        $this->totalMiscellaneous = Payment::where('academic_year_id', $activeYear?->id)
            ->where('type', 'miscellaneous')
            ->sum('amount') ?? 0;

        $this->totalRevenue = $this->totalInscriptions + $this->totalTuition + $this->totalMiscellaneous;
    }
    public function render()
    {
        return view('livewire.financial-reports');
    }
}
