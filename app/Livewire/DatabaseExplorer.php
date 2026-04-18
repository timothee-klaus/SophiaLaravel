<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class DatabaseExplorer extends Component
{
    public $tables = [];
    public $selectedTable = null;
    public $tableData = [];
    public $columns = [];

    public function mount()
    {
        $this->loadTables();
    }

    public function loadTables()
    {
        $this->tables = [];
        $allTables = DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema = \'public\'');
        foreach ($allTables as $table) {
            if ($table->table_name === 'migrations' || $table->table_name === 'cache' || $table->table_name === 'sessions') continue;
            
            $this->tables[] = [
                'name' => $table->table_name,
                'count' => DB::table($table->table_name)->count(),
            ];
        }
    }

    public function selectTable($name)
    {
        $this->selectedTable = $name;
        $this->columns = Schema::getColumnListing($name);
        $this->tableData = DB::table($name)->orderByDesc('created_at')->limit(50)->get()->map(function($item) {
            return (array) $item;
        })->toArray();
    }

    public function render()
    {
        return view('livewire.database-explorer')->layout('components.layouts.guest');
    }
}
