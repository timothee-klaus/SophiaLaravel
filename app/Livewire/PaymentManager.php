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

            // Validate miscellaneous fees cap
            if ($this->type === 'miscellaneous') {
                $tuitionFee = TuitionFee::where('level_id', $this->enrollment->level_id)
                    ->where('academic_year_id', $activeYear->id)->first();
                
                if ($tuitionFee) {
                    $totalPaidMisc = Payment::where('student_id', $this->studentId)
                        ->where('academic_year_id', $activeYear->id)
                        ->where('type', 'miscellaneous')
                        ->sum('amount');
                    
                    $remainingMisc = $tuitionFee->miscellaneous_fee - $totalPaidMisc;

                    if ($this->amount > $remainingMisc) {
                        $maxAllowed = number_format($remainingMisc, 0, ',', ' ');
                        session()->flash('error', "Le montant dépasse le plafond des frais divers. Maximum autorisé restant : {$maxAllowed} FCFA.");
                        return;
                    }
                }
            }

            $payment = $action->execute(
                $student,
                $activeYear->id,
                $this->type,
                $this->amount,
                $this->installment_number ?: null
            );
            // Generate PDF Receipt using our professional template
            $pdf = Pdf::loadView('exports.receipt-pdf', [
                'payment' => $payment
            ]);
            
            $filename = 'Reçu_' . $payment->transaction_id . '.pdf';
            $path = 'receipts/' . $payment->transaction_id . '.pdf';
            
            Storage::disk('public')->put($path, $pdf->output());
            $payment->update(['receipt_path' => $path]);
            
            $this->lastReceiptUrl = $payment->uuid; 
            $this->paymentSuccess = true;
            $this->type = 'tuition';
            $this->amount = '';
            $this->installment_number = '';
            $this->loadStudentData();
            $this->dispatch('paymentCreated');
            
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
        $activeYear = AcademicYear::where('is_current', true)->first() ?? AcademicYear::first();
        $isNewStudent = false;
        if ($this->enrollment && $activeYear) {
            $isNewStudent = $this->enrollment->student->isNew($activeYear->id);
        }

        return view('livewire.payment-manager', [
            'examBlocked' => $this->enrollment ? !$this->enrollment->isEligibleForExams() : false,
            'isNewStudent' => $isNewStudent
        ]);
    }
}
