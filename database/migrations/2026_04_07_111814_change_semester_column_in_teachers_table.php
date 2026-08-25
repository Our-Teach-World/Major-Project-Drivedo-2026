<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Semester ko JSON ya string me convert kar rahe hain. 
            // Agar JSON support nahi karta toh text() best hai.
            $table->text('semester')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->integer('semester')->nullable()->change();
        });
    }
};