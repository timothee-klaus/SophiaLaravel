<?php
namespace App\Livewire;
use Livewire\Component;
use App\Models\Student;
class GlobalSearch extends Component
{
    public $query = '';
    public $results = [];
    public function updatedQuery()
    {
        if (strlen($this->query) > 1) {
            $this->results = Student::where('first_name', 'like', '%' . $this->query . '%')
                ->orWhere('last_name', 'like', '%' . $this->query . '%')
                ->orWhere('matricule', 'like', '%' . $this->query . '%')
                ->take(5)
                ->get();
        } else {
            $this->results = [];
        }
    }
    public function render()
    {
        return view('livewire.global-search');
    }
}
