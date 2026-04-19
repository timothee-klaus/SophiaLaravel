<?php

namespace App\Livewire;

use App\Models\AuditLog;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class AuditExplorer extends Component
{
    use WithPagination;

    public $search = '';
    public $event = '';
    public $userId = '';
    public $dateFrom = '';
    public $dateTo = '';

    public function mount()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        $query = AuditLog::query()
            ->with('user')
            ->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('auditable_type', 'like', '%' . $this->search . '%')
                  ->orWhere('auditable_id', 'like', '%' . $this->search . '%')
                  ->orWhere('event', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->event) {
            $query->where('event', $this->event);
        }

        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        return view('livewire.audit-explorer', [
            'logs' => $query->paginate(20),
            'users' => User::all(),
            'events' => AuditLog::select('event')->distinct()->get()->pluck('event')
        ]);
    }
}
