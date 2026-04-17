<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Level;
use App\Models\Enrollment;

class ReenrollmentManager extends Component
{
    public $students = [];
    public $search = '';
    public $selectedStudentId = null;
    public $selectedLevelId = null;
    public $activeYear;
    public $levels;

    protected $listeners = ['academicYearChanged' => 'mount'];

    public function mount()
    {
        $this->activeYear = AcademicYear::where('is_current', true)->first();
        $this->levels = Level::all();
    }

    public function updatedSearch()
    {
        if (strlen($this->search) > 2) {
            $this->students = Student::where('first_name', 'like', '%' . $this->search . '%')
                ->orWhere('last_name', 'like', '%' . $this->search . '%')
                ->get();
        } else {
            $this->students = [];
        }
    }

    public function selectStudent($id)
    {
        $this->selectedStudentId = $id;
    }

    public function reenroll()
    {
        $this->validate([
            'selectedStudentId' => 'required',
            'selectedLevelId' => 'required',
        ]);

        if (!$this->activeYear) {
            session()->flash('error', 'Aucune année active trouvée.');
            return;
        }

        // Check if already enrolled in this active year
        $exists = Enrollment::where('student_id', $this->selectedStudentId)
            ->where('academic_year_id', $this->activeYear->id)
            ->exists();

        if ($exists) {
            session()->flash('error', 'Cet élève est déjà inscrit pour cette année active.');
            return;
        }

        Enrollment::create([
            'student_id' => $this->selectedStudentId,
            'academic_year_id' => $this->activeYear->id,
            'level_id' => $this->selectedLevelId,
            'status' => 'active',
        ]);

        $this->selectedStudentId = null;
        $this->selectedLevelId = null;
        $this->search = '';
        $this->students = [];

        session()->flash('message', 'Réinscription effectuée avec succès.');
    }

    public function render()
    {
        return view('livewire.reenrollment-manager');
    }
}
