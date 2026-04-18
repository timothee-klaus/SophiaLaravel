<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AcademicYear;
use App\Models\Level;
use App\Models\Enrollment;
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

    public function mount()
    {
        $activeYear = AcademicYear::where('is_current', true)->first();
        if ($activeYear) {
            $this->activeYearId = $activeYear->id;
            $this->levels = Level::all();
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
            } else {
                // Si rien n'est sélectionné, on ne charge rien pour éviter de surcharger
                $this->enrollments = [];
                return;
            }

            $this->enrollments = $query->get()
                ->map(function ($enrollment) {
                    // Pour le suivi des retards, "eligible" signifie "à jour"
                    $enrollment->is_eligible = $enrollment->isEligibleForExams();
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

            session()->flash('message', 'Élève débloqué avec succès.');
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

