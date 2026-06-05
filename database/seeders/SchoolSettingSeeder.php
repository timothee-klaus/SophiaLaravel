<?php

namespace Database\Seeders;

use App\Models\SchoolSetting;
use Illuminate\Database\Seeder;

class SchoolSettingSeeder extends Seeder
{
    public function run(): void
    {
        SchoolSetting::create([
            'name' => 'Institut Sophia',
            'slogan' => '«Le Don De Dieu»',
            'address' => 'Abidjan, Côte d\'Ivoire',
            'phones' => '+225 0102030405',
            'email' => 'contact@institut-sophia.com',
            'logo_path' => 'logos/logo.png',
        ]);
    }
}
