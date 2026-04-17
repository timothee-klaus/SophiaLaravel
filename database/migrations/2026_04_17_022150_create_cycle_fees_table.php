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
        Schema::create('cycle_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->enum('cycle', ['preschool', 'primary', 'college', 'lycee']);
            $table->decimal('registration_fee', 10, 2)->default(0);
            $table->decimal('miscellaneous_fee', 10, 2)->default(0);
            $table->unique(['academic_year_id', 'cycle']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cycle_fees');
    }
};
