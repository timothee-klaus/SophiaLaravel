<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('nationality')->nullable()->after('gender');
            $table->string('country')->nullable()->after('nationality');
            $table->string('birth_place')->nullable()->after('birth_date');
            $table->string('address')->nullable()->after('matricule');
            
            // Guardian / Parents info
            $table->string('guardian_name')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('guardian_email')->nullable();
            $table->string('guardian_relation')->nullable(); // Père, Mère, Tuteur, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'nationality', 'country', 'birth_place', 'address',
                'guardian_name', 'guardian_phone', 'guardian_email', 'guardian_relation'
            ]);
        });
    }
};
