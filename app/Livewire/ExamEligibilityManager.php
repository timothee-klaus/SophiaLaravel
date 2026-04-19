<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AcademicYear;
use App\Models\Level;
use App\Models\Enrollment;
use App\Models\TuitionFee;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\SchoolSetting;

class ExamEligibilityManager extends Component
{
    public $activeYearId;
    public $levels;
    public $selectedCycle = ''; 
    public $selectedLevelId = '';
    public $enrollments = [];

    public $showUnblockModal = false;
    public $selectedEnrollmentId = null;
    public $unblockReason = '';

    public $successMessage = null;
    public $errorMessage = null;

    public function closeMessage()
    {
        $this->successMessage = null;
        $this->errorMessage = null;
    }

    public function mount()
    {
        $activeYear = AcademicYear::where('is_current', true)->first();
        if ($activeYear) {
            $this->activeYearId = $activeYear->id;
            $this->levels = Level::all();
            $this->loadEnrollments();
        } else {
            $this->levels = collect();
        }
    }

    public function updatedSelectedCycle()
    {
        $this->selectedLevelId = '';
        $this->loadEnrollments();
    }

    public function updatedSelectedLevelId()
    {
        $this->loadEnrollments();
    }

    public function loadEnrollments()
    {
        if ($this->activeYearId) {
            $query = Enrollment::with(['student', 'level'])
                ->where('academic_year_id', $this->activeYearId);

            if ($this->selectedLevelId) {
                $query->where('level_id', $this->selectedLevelId);
            } elseif ($this->selectedCycle) {
                $query->whereHas('level', function ($q) {
                    $q->where('cycle', $this->selectedCycle);
                });
            }

            $this->enrollments = $query->get()
                ->map(function ($enrollment) {
                    // "is_eligible" = used for "BLOQUÉ" (Exam classes only)
                    $enrollment->is_eligible = $enrollment->isEligibleForExams();
                    
                    // "is_up_to_date" = used for "EN RETARD" (All classes)
                    // Up to date means paid at least what was due by "now"
                    $tuitionFee = \App\Models\TuitionFee::where('level_id', $enrollment->level_id)
                        ->where('academic_year_id', $enrollment->academic_year_id)
                        ->first();
                        
                    if ($tuitionFee) {
                        $requiredNow = $tuitionFee->installments()
                            ->where('due_date', '<=', now())
                            ->sum('amount');
                        
                        $paid = \App\Models\Payment::where('student_id', $enrollment->student_id)
                            ->where('academic_year_id', $enrollment->academic_year_id)
                            ->where('type', 'tuition')
                            ->sum('amount');
                            
                        $enrollment->is_up_to_date = $paid >= $requiredNow;
                    } else {
                        $enrollment->is_up_to_date = true;
                    }

                    return $enrollment;
                });
        } else {
            $this->enrollments = [];
        }
    }

    public function openUnblockModal($enrollmentId)
    {
        $this->selectedEnrollmentId = $enrollmentId;
        $this->unblockReason = '';
        $this->showUnblockModal = true;
    }

    public function unblockStudent()
    {
        $this->validate([
            'unblockReason' => 'required|string|min:5|max:255',
        ]);

        $enrollment = Enrollment::find($this->selectedEnrollmentId);
        if ($enrollment) {
            $enrollment->update([
                'is_manually_unblocked' => true,
                'manual_exam_unblock_reason' => $this->unblockReason,
            ]);

            $this->successMessage = 'Élève débloqué avec succès.';
        }

        $this->showUnblockModal = false;
        $this->loadEnrollments();
    }

    public function exportPdf()
    {
        if (!$this->selectedLevelId) {
            return;
        }

        $level = Level::find($this->selectedLevelId);
        $academicYear = AcademicYear::find($this->activeYearId);
        $schoolSetting = SchoolSetting::first();

        // Check if there are enrollments
        if (empty($this->enrollments) && count($this->enrollments) == 0) {
            $this->loadEnrollments();
        }

        $pdf = Pdf::loadView('pdf.attendance-list', [
            'level' => $level,
            'academicYear' => $academicYear,
            'enrollments' => $this->enrollments,
            'schoolSetting' => $schoolSetting,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'liste_emargement_' . str_replace(' ', '_', strtolower($level->name)) . '.pdf');
    }

    public function render()
    {
        return view('livewire.exam-eligibility-manager');
    }
}

