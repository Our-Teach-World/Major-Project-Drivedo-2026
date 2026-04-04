<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->unique(); // one profile per teacher
            $table->string('display_name', 100)->nullable();
            $table->string('profile_image', 255)->nullable(); // relative path on disk
            $table->text('bio')->nullable();
            $table->timestamps();

            $table->foreign('student_id')
                  ->references('id')
                  ->on('students')
                  ->onDelete('cascade'); // delete profile when teacher account deleted
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
