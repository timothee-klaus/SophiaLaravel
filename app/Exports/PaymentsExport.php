<?php
namespace App\Exports;
use App\Models\Payment;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class PaymentsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;
    protected $startDate;
    protected $endDate;
    protected $levelId;
    protected $academicYearId;
    public function __construct($startDate, $endDate, $levelId, $academicYearId)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->levelId = $levelId;
        $this->academicYearId = $academicYearId;
    }
    public function query()
    {
        $query = Payment::query()->with(['student', 'academicYear'])
            ->where('academic_year_id', $this->academicYearId);
        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }
        if ($this->levelId) {
            $query->whereHas('student.enrollments', function ($q) {
                $q->where('level_id', $this->levelId)
                  ->where('academic_year_id', $this->academicYearId);
            });
        }
        return $query->orderBy('created_at', 'desc');
    }
    public function headings(): array
    {
        return [
            'Transaction N°',
            'Date',
            'Matricule',
            'Nom de l\'élève',
            'Type de Frais',
            'Échéance',
            'Montant (FCFA)'
        ];
    }
    public function map($payment): array
    {
        $typeLabel = '';
        if($payment->type === 'registration') $typeLabel = "Frais d'Inscription";
        elseif($payment->type === 'miscellaneous') $typeLabel = "Frais Divers";
        else $typeLabel = "Scolarité";
        return [
            $payment->transaction_id,
            $payment->created_at->format('d/m/Y H:i'),
            $payment->student->matricule,
            $payment->student->first_name . ' ' . $payment->student->last_name,
            $typeLabel,
            $payment->installment_number ? 'T' . $payment->installment_number : '-',
            number_format($payment->amount, 0, ',', ' ')
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF1E3A8A']]],
        ];
    }
}
