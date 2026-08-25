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
        Schema::disableForeignKeyConstraints();

        // 1. Modify users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
            $table->string('username')->nullable()->unique();
            $table->enum('role', ['admin', 'student', 'teacher'])->default('student');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        });

        // 2. Add user_id to students table
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });

        // 3. Add user_id to teachers table
        Schema::table('teachers', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });

        // 4. Data migration: Copy existing students (users) to default users table
        $oldUsers = DB::table('students')->get();
        foreach ($oldUsers as $oldUser) {
            $exists = DB::table('users')->where('username', $oldUser->username)->first();
            if (!$exists) {
                // Determine timestamps
                $createdAt = isset($oldUser->created_at) ? $oldUser->created_at : now();
                $updatedAt = isset($oldUser->updated_at) ? $oldUser->updated_at : now();

                $newUserId = DB::table('users')->insertGetId([
                    'username'   => $oldUser->username,
                    'password'   => $oldUser->password ?? '',
                    'role'       => $oldUser->role,
                    'status'     => $oldUser->status,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]);

                // Update relationships
                DB::table('students')->where('id', $oldUser->id)->update(['user_id' => $newUserId]);
                DB::table('teachers')->where('student_id', $oldUser->id)->update(['user_id' => $newUserId]);
                DB::table('uploads')->where('user_id', $oldUser->id)->update(['user_id' => $newUserId]);
                DB::table('subject_teacher')->where('user_id', $oldUser->id)->update(['user_id' => $newUserId]);
                DB::table('attendances')->where('student_id', $oldUser->id)->update(['student_id' => $newUserId]);
                DB::table('attendances')->where('teacher_id', $oldUser->id)->update(['teacher_id' => $newUserId]);
            } else {
                 // Even if exists, update relationships to point to this existing user's ID
                 DB::table('students')->where('id', $oldUser->id)->update(['user_id' => $exists->id]);
                 DB::table('teachers')->where('student_id', $oldUser->id)->update(['user_id' => $exists->id]);
                 DB::table('uploads')->where('user_id', $oldUser->id)->update(['user_id' => $exists->id]);
                 DB::table('subject_teacher')->where('user_id', $oldUser->id)->update(['user_id' => $exists->id]);
                 DB::table('attendances')->where('student_id', $oldUser->id)->update(['student_id' => $exists->id]);
                 DB::table('attendances')->where('teacher_id', $oldUser->id)->update(['teacher_id' => $exists->id]);
            }
        }

        // 5. Drop columns from students
        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique('auth_username_unique');
            $table->dropColumn(['username', 'password', 'role', 'status']);
        });

        // 6. Drop student_id from teachers
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropColumn('student_id');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For brevity and keeping things rolling, progressive downfall will just require migration rollback and manually dropping added columns
    }
};
