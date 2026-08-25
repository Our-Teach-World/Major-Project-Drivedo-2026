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
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->string('branch');
            $table->string('semester');
            $table->string('day');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('subject_name');
            $table->string('subject_code')->nullable();
            $table->string('teacher_name');
            $table->string('room_no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
