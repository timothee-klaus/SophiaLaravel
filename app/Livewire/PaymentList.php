<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Payment;
use App\Models\AcademicYear;
use App\Models\Level;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentList extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $filterType = '';
    public $activeYearId;
    public $showModal = false;

    public $showUploadModal = false;
    public $uploadPaymentId = null;
    public $signedReceiptFile;

    protected $listeners = ['paymentCreated' => 'refreshList'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $activeYear = AcademicYear::where('is_current', true)->first();
        $this->activeYearId = $activeYear ? $activeYear->id : null;
    }

    public function refreshList()
    {
        $this->showModal = false;
        $this->resetPage();
    }

    public function openUploadModal($paymentId)
    {
        $this->uploadPaymentId = $paymentId;
        $this->signedReceiptFile = null;
        $this->showUploadModal = true;
    }

    public function saveSignedReceipt()
    {
        $this->validate([
            'signedReceiptFile' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $payment = Payment::find($this->uploadPaymentId);
        if ($payment) {
            $path = $this->signedReceiptFile->store('documents/signed_receipts', 'local');
            $payment->update(['signed_receipt_path' => $path]);
            session()->flash('message', 'Reçu scanné enregistré avec succès.');
        }

        $this->showUploadModal = false;
    }

    public function generateReceipt($paymentId)
    {
        $payment = Payment::with(['student', 'academicYear'])->find($paymentId);
        if (!$payment) return;

        $pdf = Pdf::loadView('exports.receipt-pdf', ['payment' => $payment]);
        $fileName = 'Recu_' . $payment->transaction_id . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }

    public function downloadReceipt($paymentId)
    {
        return $this->generateReceipt($paymentId);
    }

    public function downloadSignedReceipt($paymentId)
    {
        $payment = Payment::find($paymentId);
        if ($payment && $payment->signed_receipt_path) {
            return response()->download(storage_path('app/private/' . $payment->signed_receipt_path));
        }
    }

    public function render()
    {
        $payments = Payment::with(['student'])
            ->when($this->activeYearId, function ($query) {
                $query->where('academic_year_id', $this->activeYearId);
            })
            ->when($this->filterType, function ($query) {
                $query->where('type', $this->filterType);
            })
            ->whereHas('student', function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('matricule', 'like', '%' . $this->search . '%')
                      ->orWhere('transaction_id', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('livewire.payment-list', [
            'payments' => $payments
        ]);
    }
}

