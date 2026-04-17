<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Student;
use App\Models\TuitionFee;
use App\Models\Installment;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use App\Events\PaymentValidated;
use App\Models\AcademicYear;

class RegisterPaymentAction
{
    /**
     * Enregistre un paiement et vérifie les montants.
     *
     * @throws Exception
     */
    public function execute(Student $student, int $academicYearId, string $type, float $amount, ?int $installmentNumber = null, ?UploadedFile $receiptFile = null): Payment
    {
        $academicYear = AcademicYear::find($academicYearId);
        if ($academicYear && $academicYear->is_closed) {
            throw new Exception("Cette année académique est clôturée. Les paiements ne peuvent plus être modifiés.");
        }

        if ($type === 'tuition' && $installmentNumber !== null) {
            $enrollment = $student->enrollments()->where('academic_year_id', $academicYearId)->first();

            if (!$enrollment) {
                throw new Exception("L'élève n'est pas inscrit à cette année académique.");
            }

            $tuitionFee = TuitionFee::where('level_id', $enrollment->level_id)
                ->where('academic_year_id', $academicYearId)
                ->first();

            if (!$tuitionFee) {
                throw new Exception("Aucun frais de scolarité défini pour ce niveau.");
            }

            $installment = Installment::where('tuition_fee_id', $tuitionFee->id)
                ->where('installment_number', $installmentNumber)
                ->first();

            if (!$installment) {
                throw new Exception("La tranche sélectionnée n'existe pas.");
            }

            $alreadyPaid = Payment::where('student_id', $student->id)
                ->where('academic_year_id', $academicYearId)
                ->where('type', 'tuition')
                ->where('installment_number', $installmentNumber)
                ->sum('amount');

            $remainingForInstallment = (float) $installment->amount - (float) $alreadyPaid;

            if ($amount > $remainingForInstallment) {
                throw new Exception("Montant excédentaire. Reste à payer pour la tranche {$installmentNumber} : {$remainingForInstallment} FCFA.");
            }
        }

        $transactionId = 'RCPT-' . date('YmdHis') . '-' . Str::upper(Str::random(4));
        $receiptPath = null;

        if ($receiptFile) {
            $extension = $receiptFile->getClientOriginalExtension();
            $filename = "{$transactionId}.{$extension}";
            $receiptPath = $receiptFile->storeAs('private/receipts', $filename);
        }

        $payment = Payment::create([
            'student_id' => $student->id,
            'academic_year_id' => $academicYearId,
            'amount' => $amount,
            'type' => $type,
            'installment_number' => $installmentNumber,
            'transaction_id' => $transactionId,
            'receipt_path' => $receiptPath,
        ]);

        // Notification Temps Réel via Laravel Broadcasting
        event(new PaymentValidated($payment));

        return $payment;
    }
}
