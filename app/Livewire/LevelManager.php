<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Level;
use App\Models\AcademicYear;
use App\Models\TuitionFee;

class LevelManager extends Component
{
    public $name = '';
    public $cycle = 'preschool';
    public $total_amount = '';
    public $is_exam_class = false;
    public $academicYearId;

    public $editingLevelId = null;
    public $editName = '';
    public $editCycle = '';
    public $editIsExamClass = false;
    public $editTotalAmount = '';

    // Cycle Fees Management
    public $cycleFees = []; // preschool, primary, college, lycee
    public $editingCycleKey = null;
    public $managingTuitionFeeId = null;
    public $tempInstallments = [];
    public $installmentLevelName = '';

    protected $listeners = ['academicYearChanged' => 'refreshData'];

    public function mount()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        $activeYear = AcademicYear::where('is_current', true)->first();
        $this->academicYearId = $activeYear ? $activeYear->id : null;
        $this->loadCycleFees();
    }

    public function loadCycleFees()
    {
        $cycles = ['preschool', 'primary', 'college', 'lycee'];
        
        // Always initialize default values first to avoid "Undefined array key" in Blade
        foreach ($cycles as $cycle) {
            $this->cycleFees[$cycle] = [
                'registration_fee' => 0,
                'miscellaneous_fee' => 0,
                'exam_miscellaneous_fee' => 0,
            ];
        }

        if (!$this->academicYearId) return;

        $fees = \App\Models\CycleFee::where('academic_year_id', $this->academicYearId)->get()->keyBy('cycle');

        foreach ($cycles as $cycle) {
            if (isset($fees[$cycle])) {
                $this->cycleFees[$cycle] = [
                    'registration_fee' => $fees[$cycle]->registration_fee ?? 0,
                    'miscellaneous_fee' => $fees[$cycle]->miscellaneous_fee ?? 0,
                    'exam_miscellaneous_fee' => $fees[$cycle]->exam_miscellaneous_fee ?? 0,
                ];
            }
        }
    }

    public function startEditingCycle($cycleKey)
    {
        $this->editingCycleKey = $cycleKey;
    }

    public function cancelCycleEdit()
    {
        $this->editingCycleKey = null;
        $this->loadCycleFees();
    }

    public function updateCycleFees($cycle)
    {
        $this->validate([
            "cycleFees.$cycle.registration_fee" => 'required|numeric|min:0',
            "cycleFees.$cycle.miscellaneous_fee" => 'required|numeric|min:0',
            "cycleFees.$cycle.exam_miscellaneous_fee" => 'required|numeric|min:0',
        ]);

        \App\Models\CycleFee::updateOrCreate(
            ['academic_year_id' => $this->academicYearId, 'cycle' => $cycle],
            [
                'registration_fee' => $this->cycleFees[$cycle]['registration_fee'],
                'miscellaneous_fee' => $this->cycleFees[$cycle]['miscellaneous_fee'],
                'exam_miscellaneous_fee' => $this->cycleFees[$cycle]['exam_miscellaneous_fee'],
            ]
        );

        // Sync to regular levels tuition fees
        \App\Models\TuitionFee::where('academic_year_id', $this->academicYearId)
            ->whereHas('level', function($q) use ($cycle) {
                $q->where('cycle', $cycle)->where('is_exam_class', false);
            })
            ->update([
                'registration_fee' => $this->cycleFees[$cycle]['registration_fee'],
                'miscellaneous_fee' => $this->cycleFees[$cycle]['miscellaneous_fee'],
            ]);

        // Sync to exam levels tuition fees
        \App\Models\TuitionFee::where('academic_year_id', $this->academicYearId)
            ->whereHas('level', function($q) use ($cycle) {
                $q->where('cycle', $cycle)->where('is_exam_class', true);
            })
            ->update([
                'registration_fee' => $this->cycleFees[$cycle]['registration_fee'],
                'miscellaneous_fee' => $this->cycleFees[$cycle]['exam_miscellaneous_fee'],
            ]);

        $this->editingCycleKey = null;
        session()->flash('cycle_message_' . $cycle, 'Frais mis à jour pour ce cycle.');
    }

    public function getLevelsProperty()
    {
        return Level::withCount(['enrollments' => function($q) {
            if ($this->academicYearId) {
                $q->where('academic_year_id', $this->academicYearId);
            }
        }])->with(['tuitionFees' => function($q) {
            if ($this->academicYearId) {
                $q->where('academic_year_id', $this->academicYearId)->withCount('installments');
            }
        }])->get();
    }

    public function edit($id)
    {
        $level = Level::with(['tuitionFees' => function($q) {
            $q->where('academic_year_id', $this->academicYearId);
        }])->findOrFail($id);

        $this->editingLevelId = $id;
        $this->editName = $level->name;
        $this->editCycle = $level->cycle;
        $this->editIsExamClass = $level->is_exam_class;
        
        $fee = $level->tuitionFees->first();
        $this->editTotalAmount = $fee->total_amount ?? 0;
    }

    public function cancelEdit()
    {
        $this->editingLevelId = null;
        $this->reset(['editName', 'editCycle', 'editIsExamClass', 'editTotalAmount']);
    }

    public function update()
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editCycle' => 'required|in:preschool,primary,college,lycee',
            'editTotalAmount' => 'required|numeric|min:0',
        ]);

        $level = Level::findOrFail($this->editingLevelId);
        $level->update([
            'name' => $this->editName,
            'cycle' => $this->editCycle,
            'is_exam_class' => $this->editIsExamClass ?? false,
        ]);

        $miscFee = ($this->editIsExamClass ?? false) 
            ? ($this->cycleFees[$this->editCycle]['exam_miscellaneous_fee'] ?? 0)
            : ($this->cycleFees[$this->editCycle]['miscellaneous_fee'] ?? 0);

        $tuitionFee = TuitionFee::updateOrCreate(
            ['level_id' => $level->id, 'academic_year_id' => $this->academicYearId],
            [
                'total_amount' => $this->editTotalAmount,
                'registration_fee' => $this->cycleFees[$this->editCycle]['registration_fee'] ?? 0,
                'miscellaneous_fee' => $miscFee,
            ]
        );

        $this->cancelEdit();
        session()->flash('message', 'Classe / Niveau mise à jour avec succès.');
    }

    public function save()
    {
        if (!$this->academicYearId) {
            $activeYear = AcademicYear::where('is_current', true)->first();
            if (!$activeYear) {
                session()->flash('error', 'Veuillez définir une année académique active au préalable.');
                return;
            }
            $this->academicYearId = $activeYear->id;
        }

        $this->validate([
            'name' => 'required|string|max:255',
            'cycle' => 'required|in:preschool,primary,college,lycee',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $level = Level::create([
            'name' => $this->name,
            'cycle' => $this->cycle,
            'is_exam_class' => $this->is_exam_class,
        ]);

        $miscFee = ($this->is_exam_class)
            ? ($this->cycleFees[$this->cycle]['exam_miscellaneous_fee'] ?? 0)
            : ($this->cycleFees[$this->cycle]['miscellaneous_fee'] ?? 0);

        TuitionFee::create([
            'level_id' => $level->id,
            'academic_year_id' => $this->academicYearId,
            'total_amount' => $this->total_amount,
            'registration_fee' => $this->cycleFees[$this->cycle]['registration_fee'] ?? 0,
            'miscellaneous_fee' => $miscFee,
        ]);

        $this->reset(['name', 'cycle', 'is_exam_class', 'total_amount']);

        session()->flash('message', 'Classe / Niveau créé avec succès.');
    }

    public function manageInstallments($id)
    {
        $level = Level::with(['tuitionFees' => function($q) {
            $q->where('academic_year_id', $this->academicYearId);
        }])->findOrFail($id);

        $this->installmentLevelName = $level->name;
        $fee = $level->tuitionFees->first();
        
        if (!$fee) {
            session()->flash('error', 'Veuillez d\'abord définir les frais pour cette classe.');
            return;
        }

        // Toggle logic
        if ($this->managingTuitionFeeId === $fee->id) {
            $this->managingTuitionFeeId = null;
            return;
        }

        $this->managingTuitionFeeId = $fee->id;
        $this->tempInstallments = $fee->installments()->orderBy('installment_number')->get()->map(function($i) {
            return [
                'id' => $i->id,
                'installment_number' => $i->installment_number,
                'amount' => $i->amount,
                'due_date' => $i->due_date ? $i->due_date->format('Y-m-d') : now()->format('Y-m-d'),
            ];
        })->toArray();
    }

    public function addInstallment()
    {
        $nextNumber = count($this->tempInstallments) + 1;
        $this->tempInstallments[] = [
            'id' => null,
            'installment_number' => $nextNumber,
            'amount' => 0,
            'due_date' => now()->format('Y-m-d'),
        ];
    }

    public function removeInstallment($index)
    {
        unset($this->tempInstallments[$index]);
        $this->tempInstallments = array_values($this->tempInstallments);
        
        // Renumber
        foreach ($this->tempInstallments as $i => $inst) {
            $this->tempInstallments[$i]['installment_number'] = $i + 1;
        }
    }

    public function saveInstallments()
    {
        $fee = TuitionFee::findOrFail($this->managingTuitionFeeId);
        $totalInstallments = array_sum(array_column($this->tempInstallments, 'amount'));

        if ($totalInstallments > $fee->total_amount + 0.01) {
            session()->flash('modal_error', 'La somme des tranches (' . number_format($totalInstallments, 0, ',', ' ') . ') ne peut pas dépasser le montant total de la scolarité (' . number_format($fee->total_amount, 0, ',', ' ') . ').');
            return;
        }

        // Delete old and sync
        $fee->installments()->delete();

        foreach ($this->tempInstallments as $inst) {
            $fee->installments()->create([
                'installment_number' => $inst['installment_number'],
                'amount' => $inst['amount'],
                'due_date' => $inst['due_date'],
            ]);
        }

        $this->managingTuitionFeeId = null;
        session()->flash('message', 'Tranches de paiement configurées pour ' . $this->installmentLevelName);
    }

    public function render()
    {
        return view('livewire.level-manager');
    }
}
