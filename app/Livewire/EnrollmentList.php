<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Enrollment;
use App\Models\AcademicYear;
use App\Models\Level;

class EnrollmentList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterLevelId = '';
    public $showModal = false;
    public $activeYearId;

    protected $listeners = ['enrollmentCreated' => 'refreshList'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterLevelId()
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

    public function deleteEnrollment($id)
    {
        $enrollment = Enrollment::findOrFail($id);

        // Delete associated payments if necessary
        \App\Models\Payment::where('student_id', $enrollment->student_id)
            ->where('academic_year_id', $enrollment->academic_year_id)
            ->delete();

        $enrollment->delete();
        session()->flash('message', 'Inscription supprimée avec succès.');
    }

    public function render()
    {
        $levels = Level::orderBy('name')->get();

        $enrollments = Enrollment::with(['student', 'level'])
            ->when($this->activeYearId, function ($query) {
                $query->where('academic_year_id', $this->activeYearId);
            })
            ->when($this->filterLevelId, function ($query) {
                $query->where('level_id', $this->filterLevelId);
            })
            ->whereHas('student', function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('matricule', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('livewire.enrollment-list', [
            'enrollments' => $enrollments,
            'levels' => $levels,
        ]);
    }
}
