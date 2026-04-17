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
        Schema::create('tuition_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')->constrained()->restrictOnDelete();
            $table->foreignId('academic_year_id')->constrained()->restrictOnDelete();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('registration_fee', 10, 2);
            $table->decimal('miscellaneous_fee', 10, 2);
            $table->timestamps();

            $table->unique(['level_id', 'academic_year_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuition_fees');
    }
};
