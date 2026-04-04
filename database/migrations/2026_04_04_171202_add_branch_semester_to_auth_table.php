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
        Schema::table('auth', function (Blueprint $table) {
            $table->enum('branch', [
                'Civil Engineering',
                'Mechanical Engineering',
                'Electrical Engineering',
                'Electronics Engineering (EL)',
                'Computer Engineering/Science & Engineering',
                'Instrumentation & Control Plastic Technology',
                'Chemical Engineering',
            ])->nullable()->after('role');

            $table->tinyInteger('semester')->unsigned()->nullable()->after('branch')
                  ->comment('1 to 6 (3 years, 2 semesters each)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auth', function (Blueprint $table) {
            $table->dropColumn(['branch', 'semester']);
        });
    }
};
