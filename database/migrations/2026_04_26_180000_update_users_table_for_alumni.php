<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add alumni to role enum in users table
        // We use raw SQL because Laravel doesn't support updating enums natively in all versions/drivers
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'student', 'teacher', 'alumni') NOT NULL DEFAULT 'student'");

        // 2. Add alumni profile fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('company')->nullable()->after('email');
            $table->text('bio')->nullable()->after('company');
            $table->enum('application_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['company', 'bio', 'application_status']);
        });

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'student', 'teacher') NOT NULL DEFAULT 'student'");
    }
};
