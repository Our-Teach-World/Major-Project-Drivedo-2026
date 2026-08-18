<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Ye tumhari saari branches ki list hai (Purana naam aur Naya naam dono hain)
        $tempEnum = "'Civil Engineering', 'Mechanical Engineering', 'Electrical Engineering', 'Electronics Engineering (EL)', 'Computer Engineering/Science & Engineering', 'Computer Science & Engineering', 'Instrumentation & Control Plastic Technology', 'Chemical Engineering'";
        
        // Final list jisme purana naam nahi hai
        $finalEnum = "'Civil Engineering', 'Mechanical Engineering', 'Electrical Engineering', 'Electronics Engineering (EL)', 'Computer Science & Engineering', 'Instrumentation & Control Plastic Technology', 'Chemical Engineering'";

        $tables = ['users', 'students', 'teachers', 'subjects'];

        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'branch')) {
                // 1. Dono naam allow karo taaki data crash na ho
                DB::statement("ALTER TABLE {$table} MODIFY branch ENUM({$tempEnum})");
                
                // 2. Purane data ko naye data se replace (Update) karo
                DB::statement("UPDATE {$table} SET branch = 'Computer Science & Engineering' WHERE branch = 'Computer Engineering/Science & Engineering'");
                
                // 3. Purane naam ko ENUM list se permanently hata do
                DB::statement("ALTER TABLE {$table} MODIFY branch ENUM({$finalEnum})");
            }
        }
    }

    public function down()
    {
        // Reverse karne ka code
    }
};