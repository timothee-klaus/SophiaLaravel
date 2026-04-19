<?php

namespace App\Livewire;

use App\Models\Student;
use App\Models\Level;
use App\Models\AcademicYear;
use App\Models\TuitionFee;
use Livewire\Component;
use Livewire\WithPagination;

class StudentList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterGender = '';
    public $filterLevel = '';
    
    public $academicYearId;

    // Edit state
    public $editStudentId = null;
    public $editFirstName;
    public $editLastName;
    public $editMatricule;
    public $editGender;
    public $editBirthDate;
    public $editBirthPlace;
    public $editNationality;
    public $editAddress;
    public $editGuardianName;
    public $editGuardianPhone;
    public $editGuardianEmail;
    public $editGuardianRelation;
    public $editGuardianProfession;
    public $showEditModal = false;

    // Profile state
    public $showProfileModal = false;
    public $profileStudent = null;
    public $profileActiveEnrollment = null;
    public $profileTuitionFee = 0;
    public $profileTuitionPaid = 0;
    public $profileTuitionBalance = 0;
    public $profileRegistrationPaid = 0;
    public $profileMiscellaneousPaid = 0;
    public $profileRegistrationFee = 0;
    public $profileMiscellaneousFee = 0;

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
        $this->academicYearId = $activeYear ? $activeYear->id : null;
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterGender() { $this->resetPage(); }
    public function updatingFilterLevel() { $this->resetPage(); }

    public function viewProfile($id)
    {
        $this->profileStudent = Student::with(['enrollments' => function($q) {
            $q->orderBy('created_at', 'desc')->with('level');
        }])->findOrFail($id);
        
        $this->profileActiveEnrollment = $this->profileStudent->enrollments->firstWhere('academic_year_id', $this->academicYearId);
        
        $this->profileTuitionFee = 0;
        $this->profileTotalPaid = 0;
        $this->profileBalance = 0;

        if ($this->profileActiveEnrollment) {
            $tuition = TuitionFee::where('level_id', $this->profileActiveEnrollment->level_id)
                ->where('academic_year_id', $this->academicYearId)
                ->first();
            
            $this->profileTuitionFee = $tuition ? (float)$tuition->total_amount : 0;
            $this->profileRegistrationFee = $tuition ? (float)$tuition->registration_fee : 0;
            $this->profileMiscellaneousFee = $tuition ? (float)$tuition->miscellaneous_fee : 0;

            $this->profileTuitionPaid = $this->profileActiveEnrollment->getTuitionPaid();
            $this->profileRegistrationPaid = $this->profileActiveEnrollment->getRegistrationPaid();
            $this->profileMiscellaneousPaid = $this->profileActiveEnrollment->getMiscellaneousPaid();
            
            $this->profileTuitionBalance = $this->profileTuitionFee - $this->profileTuitionPaid;
        }

        $this->showProfileModal = true;
    }

    public function closeProfile()
    {
        $this->showProfileModal = false;
        $this->profileStudent = null;
    }

    public function editStudent($id)
    {
        $student = Student::findOrFail($id);
        $this->editStudentId = $student->id;
        $this->editFirstName = $student->first_name;
        $this->editLastName = $student->last_name;
        $this->editMatricule = $student->matricule;
        $this->editGender = $student->gender;
        $this->editBirthDate = $student->birth_date ? $student->birth_date->format('Y-m-d') : null;
        $this->editBirthPlace = $student->birth_place;
        $this->editNationality = $student->nationality;
        $this->editAddress = $student->address;
        $this->editGuardianName = $student->guardian_name;
        $this->editGuardianPhone = $student->guardian_phone;
        $this->editGuardianEmail = $student->guardian_email;
        $this->editGuardianRelation = $student->guardian_relation;
        $this->editGuardianProfession = $student->guardian_profession;
        $this->showEditModal = true;
    }

    public function updateStudent()
    {
        $this->validate([
            'editFirstName' => 'required|string|max:255',
            'editLastName' => 'required|string|max:255',
            'editMatricule' => 'required|string|max:255|unique:students,matricule,' . $this->editStudentId,
            'editGender' => 'required|in:M,F',
            'editBirthDate' => 'required|date',
            'editGuardianEmail' => 'nullable|email',
        ]);

        $student = Student::findOrFail($this->editStudentId);
        $student->update([
            'first_name' => $this->editFirstName,
            'last_name' => $this->editLastName,
            'matricule' => $this->editMatricule,
            'gender' => $this->editGender,
            'birth_date' => $this->editBirthDate,
            'birth_place' => $this->editBirthPlace,
            'nationality' => $this->editNationality,
            'address' => $this->editAddress,
            'guardian_name' => $this->editGuardianName,
            'guardian_phone' => $this->editGuardianPhone,
            'guardian_email' => $this->editGuardianEmail,
            'guardian_relation' => $this->editGuardianRelation,
            'guardian_profession' => $this->editGuardianProfession,
        ]);

        $this->showEditModal = false;
        $this->successMessage = 'Élève mis à jour avec succès.';
    }

    public function deleteStudent($id)
    {
        $student = Student::findOrFail($id);
        $student->enrollments()->delete();
        $student->payments()->delete();
        $student->delete();

        $this->successMessage = 'Élève supprimé avec succès.';
    }

    public function render()
    {
        $query = Student::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('matricule', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterGender) {
            $query->where('gender', $this->filterGender);
        }

        if ($this->filterLevel) {
            // Uniquement les élèves inscrits dans ce niveau pour l'année courante
            $query->whereHas('enrollments', function ($q) {
                $q->where('level_id', $this->filterLevel)
                  ->where('academic_year_id', $this->academicYearId)
                  ->where('status', 'active');
            });
        }

        // Ajout d'une sous-requête pour avoir le current_enrollment sans N+1 excessif ou on peut le lazy load dans la blade
        $students = $query->with(['enrollments' => function($q) {
            $q->where('academic_year_id', $this->academicYearId)->with('level');
        }])->orderBy('last_name')->paginate(15);

        $levels = Level::all();

        return view('livewire.student-list', [
            'students' => $students,
            'levels' => $levels
        ]);
    }
}
