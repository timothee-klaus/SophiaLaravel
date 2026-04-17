<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('birth_certificate_path')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('attestation_path')->nullable();
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->string('signed_receipt_path')->nullable();
        });
    }
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['birth_certificate_path', 'photo_path', 'attestation_path']);
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['signed_receipt_path']);
        });
    }
};
