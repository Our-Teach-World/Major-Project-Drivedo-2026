<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            // 1. Purane string column ko delete karo
            if (Schema::hasColumn('attendances', 'subject_name')) {
                $table->dropColumn('subject_name');
            }

            // 2. Naya subject_id column add karo (unsignedBigInteger zaroori hai foreign key ke liye)
            $table->unsignedBigInteger('subject_id')->nullable()->after('student_id');

            // 3. Foreign key constraint lagana (Optional par recommended)
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');
            $table->string('subject_name')->nullable();
        });
    }
};