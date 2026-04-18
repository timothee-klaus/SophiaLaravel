<?php
namespace App\Livewire;
use App\Models\AcademicYear;
use App\Models\Enrollment;
use App\Models\Installment;
use App\Models\Level;
use App\Models\Payment;
use App\Models\Student;
use App\Models\TuitionFee;
use App\Services\RegisterPaymentAction;
use Livewire\Component;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use App\Models\SchoolSetting;
use Illuminate\Support\Facades\Storage;
class PaymentManager extends Component
{
    public $search = '';
    public $students = [];
    public $studentId = null;
    public $enrollment;
    public $tuitionFee;
    public $installments = [];
    public $payments = [];
    // Form
    public $type = 'tuition';
    public $amount = '';
    public $installment_number = '';
    public $paymentSuccess = false;
    public $lastReceiptUrl = null;
    public function updatedSearch()
    {
        if (strlen($this->search) > 1) {
            $this->students = Student::where('first_name', 'like', '%' . $this->search . '%')
                ->orWhere('last_name', 'like', '%' . $this->search . '%')
                ->orWhere('matricule', 'like', '%' . $this->search . '%')
                ->take(5)->get();
        } else {
            $this->students = [];
        }
    }
    public function selectStudent($id)
    {
        $this->studentId = $id;
        $this->search = '';
        $this->students = [];
        $this->loadStudentData();
    }
    public function loadStudentData()
    {
        $activeYear = AcademicYear::where('is_current', true)->first() ?? AcademicYear::first();
        $this->enrollment = Enrollment::with('student', 'level')->where('student_id', $this->studentId)
            ->where('academic_year_id', $activeYear->id)
            ->first();
        if ($this->enrollment) {
            $this->tuitionFee = TuitionFee::where('level_id', $this->enrollment->level_id)
                ->where('academic_year_id', $activeYear->id)->first();
            if ($this->tuitionFee) {
                $this->installments = Installment::where('tuition_fee_id', $this->tuitionFee->id)->orderBy('installment_number')->get();
            }
            $this->payments = Payment::where('student_id', $this->studentId)
                ->where('academic_year_id', $activeYear->id)
                ->orderBy('created_at', 'desc')->get();
        }
    }
    public function updatedType($value)
    {
        if ($value === 'miscellaneous') {
            if ($this->enrollment && $this->enrollment->level) {
                $levelName = strtolower($this->enrollment->level->name);
                $cycle = $this->enrollment->level->cycle;
                $isExamClass = $this->enrollment->level->is_exam_class;
                if (str_contains($levelName, 'cm2')) {
                    $this->amount = 2000;
                } elseif (in_array($cycle, ['college', 'lycee']) && $isExamClass) {
                    $this->amount = 3000;
                } else {
                    $this->amount = 1000;
                }
            }
        } else {
            $this->amount = '';
        }
        $this->installment_number = '';
    }

    public function updatedInstallmentNumber($value)
    {
        if ($this->type === 'tuition' && $value) {
            $ins = $this->installments->where('installment_number', $value)->first();
            if ($ins) {
                $paid = $this->payments->where('type', 'tuition')
                    ->where('installment_number', $value)
                    ->sum('amount');
                $this->amount = $ins->amount - $paid;
            }
        }
    }
    public function savePayment(RegisterPaymentAction $action)
    {
        $this->validate([
            'type' => 'required|in:registration,miscellaneous,tuition',
            'amount' => 'required|numeric|min:1',
            'installment_number' => 'nullable|required_if:type,tuition|integer',
        ]);
        try {
            $student = Student::find($this->studentId);
            $activeYear = AcademicYear::where('is_current', true)->first() ?? AcademicYear::first();
            $payment = $action->execute(
                $student,
                $activeYear->id,
                $this->type,
                $this->amount,
                $this->installment_number ?: null
            );
            // Generate PDF Receipt
            // Generate PDF Receipt using our professional template
            $pdf = Pdf::loadView('exports.receipt-pdf', [
                'payment' => $payment
            ]);
            
            $filename = 'Reçu_' . $payment->transaction_id . '.pdf';
            $path = 'receipts/' . $payment->transaction_id . '.pdf';
            
            // Store it just in case, but we prefer on-the-fly generation
            Storage::disk('public')->put($path, $pdf->output());
            $payment->update(['receipt_path' => $path]);
            
            $this->lastReceiptUrl = $payment->uuid; // Using UUID for secure link resolution
            $this->paymentSuccess = true;
            $this->type = 'tuition';
            $this->amount = '';
            $this->installment_number = '';
            $this->loadStudentData();
            $this->dispatch('paymentCreated');
            session()->flash('message', 'Paiement enregistré avec succès.');
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
    public function downloadReceipt($paymentId)
    {
        $payment = Payment::with(['student', 'academicYear'])->find($paymentId);
        if (!$payment) return;

        $pdf = Pdf::loadView('exports.receipt-pdf', ['payment' => $payment]);
        $fileName = 'Recu_' . $payment->transaction_id . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }
    public function render()
    {
        $examBlocked = false;
        if ($this->enrollment && $this->installments) {
            $levelName = strtolower($this->enrollment->level->name);
            $cycle = strtolower($this->enrollment->level->cycle);
            $isExamClass = in_array($cycle, ['college', 'lycee']) && $this->enrollment->level->is_exam_class;
            if ($isExamClass || str_contains($levelName, 'cm2') || str_contains($levelName, '3ème') || str_contains($levelName, '1ère') || str_contains($levelName, 'tle')) {
                // If the current date is after December 31st (month >= 1)
                // Actually to make it check strictly, if now() is Jan to Dec, the "31st December" refers to the first year of the academic year.
                // We enforce the check if the date is passed.
                $decemberDeadline = false;
                $activeYear = AcademicYear::where('is_current', true)->first() ?? AcademicYear::first();
                if ($activeYear) {
                    $years = explode('-', $activeYear->name);
                    $start_year = count($years) > 1 ? trim($years[0]) : now()->year;
                    $deadline = \Carbon\Carbon::parse("$start_year-12-31")->endOfDay();
                    if (now()->greaterThan($deadline)) {
                        $decemberDeadline = true;
                    }
                }

                if ($decemberDeadline) {
                    $requiredForExam = $this->installments->whereIn('installment_number', [1, 2])->sum('amount') ?? 0;
                    $paidForExam = $this->payments->where('type', 'tuition')->whereIn('installment_number', [1, 2])->sum('amount') ?? 0;
                    if ($paidForExam < $requiredForExam) {
                        $examBlocked = true;
                    }
                }
            }
        }
        return view('livewire.payment-manager', [
            'examBlocked' => $examBlocked
        ]);
    }
}
