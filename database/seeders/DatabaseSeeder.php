<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key checks to truncate tables if needed (though migrate:fresh handles this)
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([
            UserSeeder::class,
            StructureSeeder::class,
            SchoolSettingSeeder::class,
            MassiveDataSeeder::class,
            AuditLogSeeder::class,
        ]);

        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
