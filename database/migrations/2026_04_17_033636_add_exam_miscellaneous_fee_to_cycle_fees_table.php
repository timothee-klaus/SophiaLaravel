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
        Schema::table('cycle_fees', function (Blueprint $table) {
            $table->decimal('exam_miscellaneous_fee', 12, 2)->default(0)->after('miscellaneous_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cycle_fees', function (Blueprint $table) {
            $table->dropColumn('exam_miscellaneous_fee');
        });
    }
};
