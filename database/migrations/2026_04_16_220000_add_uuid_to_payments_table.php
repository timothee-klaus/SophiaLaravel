<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Payment;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'uuid')) {
                $table->uuid('uuid')->nullable()->after('id');
            }
        });

        // Generate UUIDs for existing payments using raw SQL for Postgres compatibility
        DB::statement('UPDATE payments SET uuid = gen_random_uuid() WHERE uuid IS NULL');

        // Make it non-nullable after filling and add unique constraint
        Schema::table('payments', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
