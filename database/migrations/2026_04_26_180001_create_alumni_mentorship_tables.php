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
        // 1. Mentorship Requests
        Schema::create('mentorship_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('alumni_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // 2. Mentorship Sessions
        Schema::create('mentorship_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('alumni_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mentorship_request_id')->constrained('mentorship_requests')->onDelete('cascade');
            $table->string('title');
            $table->timestamp('scheduled_at');
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Mentorship Session Messages
        Schema::create('session_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('mentorship_sessions')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->enum('sender_type', ['student', 'alumni']);
            $table->text('message');
            $table->timestamps();
        });

        // 4. Direct Messages (for general communication)
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->timestamps();
        });

        // 5. Mentorship Settings
        Schema::create('mentorship_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('AlumniConnect');
            $table->string('contact_email')->default('admin@example.com');
            $table->integer('max_requests')->default(5);
            $table->integer('session_duration')->default(60); // minutes
            $table->text('terms')->nullable();
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentorship_settings');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('session_messages');
        Schema::dropIfExists('mentorship_sessions');
        Schema::dropIfExists('mentorship_requests');
    }
};
