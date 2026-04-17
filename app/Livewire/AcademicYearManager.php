<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AcademicYear;

class AcademicYearManager extends Component
{
    public $name;
    public $academicYears;

    public function mount()
    {
        $this->loadYears();
    }

    public function loadYears()
    {
        $this->academicYears = AcademicYear::orderBy('id', 'desc')->get();
    }

    public function createYear()
    {
        $this->validate([
            'name' => 'required|string|unique:academic_years,name',
        ]);

        AcademicYear::create([
            'name' => $this->name,
            'is_current' => false,
            'is_closed' => false,
        ]);

        $this->name = '';
        $this->loadYears();
        session()->flash('message', 'Année académique créée.');
    }

    public function setActive($id)
    {
        AcademicYear::where('id', '!=', $id)->update(['is_current' => false]);
        $year = AcademicYear::findOrFail($id);
        $year->is_current = true;
        // If it's closed, we can't make it active, but let's assume active overrides or we unclose it
        $year->save();
        $this->loadYears();
        $this->dispatch('academicYearChanged');
        session()->flash('message', "L'année $year->name a été définie comme active.");
    }

    public function closeYear($id)
    {
        $year = AcademicYear::findOrFail($id);
        $year->is_closed = true;
        $year->is_current = false;
        $year->save();
        $this->loadYears();
        $this->dispatch('academicYearChanged');
        session()->flash('message', "L'année $year->name a été clôturée.");
    }

    public function render()
    {
        return view('livewire.academic-year-manager');
    }
}
