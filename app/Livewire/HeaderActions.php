<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AcademicYear;

class HeaderActions extends Component
{
    public $academicYears = [];
    public $activeYearId;

    public function mount()
    {
        $this->academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $activeYear = $this->academicYears->where('is_current', true)->first();
        $this->activeYearId = $activeYear ? $activeYear->id : null;
    }

    public function switchYear($id)
    {
        if (AcademicYear::where('id', $id)->exists()) {
            AcademicYear::query()->update(['is_current' => false]);
            AcademicYear::where('id', $id)->update(['is_current' => true]);
            
            // Reload the entire page to apply global changes
            $this->redirect(request()->header('Referer') ?? '/dashboard');
        }
    }

    public function markAsRead()
    {
        if (auth()->check()) {
            auth()->user()->unreadNotifications->markAsRead();
        }
    }

    public function render()
    {
        $notifications = auth()->check() ? auth()->user()->unreadNotifications()->latest()->take(5)->get() : collect();
        $activeYear = $this->academicYears->firstWhere('id', $this->activeYearId);

        return view('livewire.header-actions', [
            'notifications' => $notifications,
            'activeYear' => $activeYear
        ]);
    }
}
