<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SchoolSetting;
use Illuminate\Support\Facades\Storage;

class SchoolSettingsManager extends Component
{
    use WithFileUploads;

    public $name;
    public $slogan;
    public $address;
    public $phones;
    public $email;
    public $logo;
    public $currentLogoPath;

    public function mount()
    {
        $setting = SchoolSetting::first();
        $this->name = $setting->name ?? 'INSTITUT SCOLAIRE SOPHIA';
        $this->slogan = $setting->slogan ?? '«Le Don De Dieu»';
        $this->address = $setting->address ?? '';
        $this->phones = $setting->phones ?? '';
        $this->email = $setting->email ?? '';
        $this->currentLogoPath = $setting->logo_path ?? null;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string',
            'slogan' => 'nullable|string',
            'address' => 'nullable|string',
            'phones' => 'nullable|string',
            'email' => 'nullable|email',
            'logo' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $setting = SchoolSetting::first();
        if (!$setting) {
            $setting = new SchoolSetting();
        }
        $setting->name = $this->name;
        $setting->slogan = $this->slogan;
        $setting->address = $this->address;
        $setting->phones = $this->phones;
        $setting->email = $this->email;

        if ($this->logo) {
            // Delete old logo
            if ($setting->logo_path && Storage::disk('public')->exists($setting->logo_path)) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            $path = $this->logo->store('logos', 'public');
            $setting->logo_path = $path;
            $this->currentLogoPath = $path;
        }

        $setting->save();

        session()->flash('message', 'Paramètres mis à jour avec succès.');
    }

    public function render()
    {
        return view('livewire.school-settings-manager');
    }
}
