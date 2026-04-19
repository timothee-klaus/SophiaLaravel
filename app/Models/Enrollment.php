<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\Auditable;

class Enrollment extends Model
{
    use Auditable;
    protected $fillable = [
        'student_id',
        'level_id',
        'academic_year_id',
        'has_complete_file',
        'status',
        'is_manually_unblocked',
        'manual_exam_unblock_reason',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function getTuitionPaid(): float
    {
        return (float) Payment::where('student_id', $this->student_id)
            ->where('academic_year_id', $this->academic_year_id)
            ->where('type', 'tuition')
            ->sum('amount');
    }

    public function getRegistrationPaid(): float
    {
        return (float) Payment::where('student_id', $this->student_id)
            ->where('academic_year_id', $this->academic_year_id)
            ->where('type', 'registration')
            ->sum('amount');
    }

    public function getMiscellaneousPaid(): float
    {
        return (float) Payment::where('student_id', $this->student_id)
            ->where('academic_year_id', $this->academic_year_id)
            ->where('type', 'miscellaneous')
            ->sum('amount');
    }

    public function getTotalPaid(): float
    {
        // Combined for total cash collected (original meaning, but we should be careful in UI)
        return (float) Payment::where('student_id', $this->student_id)
            ->where('academic_year_id', $this->academic_year_id)
            ->sum('amount');
    }

    public function isEligibleForExams(): bool
    {
        if ($this->is_manually_unblocked) {
            return true;
        }

        if (! $this->level->is_exam_class) {
            return true;
        }

        $academicYear = $this->academicYear;
        // Parse the start year from the name (e.g. '2026-2027' -> 2026)
        $startYearStr = explode('-', $academicYear->name)[0];
        $deadline = \Carbon\Carbon::parse("$startYearStr-12-31")->endOfDay();

        // Before deadline, everyone in exam class is eligible (pending payment)
        if (now()->lessThanOrEqualTo($deadline)) {
            return true;
        }

        // After December 31st, check for the first TWO installments
        $tuitionFee = TuitionFee::where('level_id', $this->level_id)
            ->where('academic_year_id', $this->academic_year_id)
            ->first();

        if (! $tuitionFee) {
            return false;
        }

        $installments = $tuitionFee->installments()
            ->orderBy('installment_number')
            ->take(2)
            ->get();
        
        if ($installments->count() >= 2) {
            // Must have paid at least the sum of the first two installments
            $requiredAmount = $installments->sum('amount');
        } elseif ($installments->count() == 1) {
            // Only one installment defined? Assume it's the 1st one, but we need "two" as per rule.
            // If only one exists, maybe it's 100%. We take it as the requirement.
            $requiredAmount = $installments->first()->amount;
        } else {
            // No tranches? Fallback to a percentage (e.g. 50% for the first two "halves")
            $requiredAmount = $tuitionFee->total_amount * 0.50;
        }

        $tuitionPaid = (float) Payment::where('student_id', $this->student_id)
            ->where('academic_year_id', $this->academic_year_id)
            ->where('type', 'tuition')
            ->sum('amount');

        return $tuitionPaid >= (float) $requiredAmount;
    }
}
