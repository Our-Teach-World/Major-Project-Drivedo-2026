<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Nullable isliye lagaya hai taaki agar pehle se koi subject add ho, toh error na aaye
            $table->string('code')->nullable()->after('name'); 
            $table->string('branch')->nullable()->after('semester'); 
        });
    }

    public function down()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['code', 'branch']);
        });
    }
};