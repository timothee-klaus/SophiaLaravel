<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
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

    public function getTotalPaid(): float
    {
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

        if (now()->lessThanOrEqualTo($deadline)) {
            return true;
        }

        $tuitionFee = TuitionFee::where('level_id', $this->level_id)
            ->where('academic_year_id', $this->academic_year_id)
            ->first();

        if (! $tuitionFee) {
            return false;
        }

        $installmentsCount = $tuitionFee->installments()->count();
        
        if ($installmentsCount > 0) {
            $requiredAmount = $tuitionFee->installments()
                ->where('due_date', '<', now())
                ->sum('amount');
        } else {
            $requiredAmount = $tuitionFee->total_amount * 0.75; // Default: 75% required by exam time if no tranches configured
        }

        $tuitionPaid = (float) Payment::where('student_id', $this->student_id)
            ->where('academic_year_id', $this->academic_year_id)
            ->where('type', 'tuition')
            ->sum('amount');

        return $tuitionPaid >= (float) $requiredAmount;
    }
}
