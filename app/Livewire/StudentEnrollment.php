<?php
namespace App\Livewire;
use App\Models\AcademicYear;
use App\Models\Level;
use App\Services\RegisterStudentAction;
use Livewire\Component;
use Livewire\WithFileUploads;

class StudentEnrollment extends Component
{
    use WithFileUploads;

    public int $step = 1;
    // Étape 1 : Infos Élève
    public string $first_name = '';
    public string $last_name = '';
    public string $birth_date = '';
    public string $gender = '';
    public string $birth_place = '';
    public string $nationality = '';
    public string $address = '';

    // Guardian / Parents info
    public string $guardian_name = '';
    public string $guardian_phone = '';
    public string $guardian_email = '';
    public string $guardian_relation = '';
    public string $guardian_profession = '';

    // Étape documents
    public $birth_certificate;
    public $photo;
    public $attestation;

    // Étape 2 : Cycle & Niveau
    public string $cycle = '';
    public $level_id = null;
    public $availableLevels = [];
    public array $documents = [];
    // Étape 3 : Frais initiaux
    public float $registrationFee = 0;
    // Résultat
    public $newStudentId = null;

    // L'année académique
    public $academic_year_id;
    public $academicYears = [];

    public function mount()
    {
        $this->academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $active = $this->academicYears->where('is_current', true)->first() ?? $this->academicYears->first();
        $this->academic_year_id = $active ? $active->id : null;
    }

    public function updatedAcademicYearId($value)
    {
        $this->refreshLevels();
    }

    public function updatedCycle($value)
    {
        $this->refreshLevels();
        // Mise à jour de la Checklist
        if (in_array($value, ['preschool', 'primary'])) {
            $this->documents = [
                'Livret scolaire',
                'Attestation de passage ou certificat de scolarité',
                '2 copies d\'acte de naissance',
                '2 photos d\'identité',
                'Un sous-main'
            ];
        } else if (in_array($value, ['college', 'lycee'])) {
            $this->documents = [
                'Bulletins de notes de l\'année écoulée',
                'Attestation de fréquentation / Exéat',
                '2 copies d\'acte de naissance',
                '2 photos d\'identité',
                'Un sous-main'
            ];
        } else {
            $this->documents = [];
        }
    }

    private function refreshLevels()
    {
        if ($this->cycle) {
            $this->availableLevels = \App\Models\Level::where('cycle', $this->cycle)->get();
        } else {
            $this->availableLevels = [];
        }
        $this->level_id = null;
        $this->registrationFee = 0;
    }

    public function updatedLevelId($value)
    {
        if ($value && $this->academic_year_id) {
            $fee = \App\Models\TuitionFee::where('level_id', $value)
                ->where('academic_year_id', $this->academic_year_id)
                ->first();
            $this->registrationFee = $fee ? $fee->registration_fee : 0;
        } else {
            $this->registrationFee = 0;
        }
    }

    public function nextStep()
    {
        if ($this->step === 1) {
            $this->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'birth_date' => 'required|date',
                'gender' => 'required|in:M,F',
                'guardian_email' => 'nullable|email',
                'birth_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'photo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'attestation' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);
        } elseif ($this->step === 2) {
            $this->validate([
                'academic_year_id' => 'required|exists:academic_years,id',
                'cycle' => 'required|in:preschool,primary,college,lycee',
                'level_id' => 'required|exists:levels,id',
            ]);
        }
        $this->step++;
    }
    public function previousStep()
    {
        $this->step--;
    }
    public function submit(\App\Services\RegisterStudentAction $action)
    {
        $birthCertPath = $this->birth_certificate ? $this->birth_certificate->store('documents/birth_certificates', 'local') : null;
        $photoPath = $this->photo ? $this->photo->store('documents/photos', 'local') : null;
        $attestationPath = $this->attestation ? $this->attestation->store('documents/attestations', 'local') : null;

        $studentData = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'birth_place' => $this->birth_place,
            'nationality' => $this->nationality,
            'address' => $this->address,
            'guardian_name' => $this->guardian_name,
            'guardian_phone' => $this->guardian_phone,
            'guardian_email' => $this->guardian_email,
            'guardian_relation' => $this->guardian_relation,
            'guardian_profession' => $this->guardian_profession,
            'birth_certificate_path' => $birthCertPath,
            'photo_path' => $photoPath,
            'attestation_path' => $attestationPath,
        ];

        $student = $action->execute($studentData, $this->level_id, $this->academic_year_id);
        $this->newStudentId = $student->id;
        $this->step = 5; // Écran de validation finale
        $this->dispatch('enrollmentCreated');
    }
    public function closeForm()
    {
        $this->reset();
        $this->dispatch('enrollmentCreated');
    }
    public function render()
    {
        return view('livewire.student-enrollment');
    }
}
