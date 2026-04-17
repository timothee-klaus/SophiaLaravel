<?php
namespace App\Exports;
use App\Models\Enrollment;
use App\Models\TuitionFee;
use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class StudentsBalanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;
    protected $levelId;
    protected $academicYearId;
    public function __construct($levelId, $academicYearId)
    {
        $this->levelId = $levelId;
        $this->academicYearId = $academicYearId;
    }
    public function collection()
    {
        $query = Enrollment::with(['student', 'level'])->where('academic_year_id', $this->academicYearId);
        if ($this->levelId) {
            $query->where('level_id', $this->levelId);
        }
        return $query->get()->map(function($enrollment) {
            $totalPaid = Payment::where('student_id', $enrollment->student_id)
                ->where('academic_year_id', $this->academicYearId)
                ->sum('amount');
            $tuitionFee = TuitionFee::where('level_id', $enrollment->level_id)
                ->where('academic_year_id', $this->academicYearId)->first();
            $totalRequired = $tuitionFee ? ($tuitionFee->total_amount + $tuitionFee->registration_fee + $tuitionFee->miscellaneous_fee) : 0;
            $balance = max(0, $totalRequired - $totalPaid);
            $enrollment->balance = $balance;
            $enrollment->total_paid = $totalPaid;
            $enrollment->total_required = $totalRequired;
            return $enrollment;
        });
    }
    public function headings(): array
    {
        return [
            'Matricule',
            'Nom',
            'Prénom',
            'Classe',
            'Scolarité Totale',
            'Total Payé',
            'Reste à Payer (Impayé)'
        ];
    }
    public function map($enrollment): array
    {
        return [
            $enrollment->student->matricule,
            $enrollment->student->last_name,
            $enrollment->student->first_name,
            $enrollment->level->name,
            number_format($enrollment->total_required, 0, ',', ' '),
            number_format($enrollment->total_paid, 0, ',', ' '),
            number_format($enrollment->balance, 0, ',', ' ')
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF1E3A8A']]],
        ];
    }
}
