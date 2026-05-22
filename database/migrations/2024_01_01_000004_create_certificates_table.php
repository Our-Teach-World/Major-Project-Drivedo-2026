<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_id')->unique(); // e.g. CERT-2024-ABC12345
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('template_id')->constrained('certificate_templates')->onDelete('cascade');
            $table->foreignId('issued_by')->constrained('users')->onDelete('cascade');

            // Student info
            $table->string('student_name');
            $table->string('student_email');
            $table->string('enrollment_number'); // Unique per student
            $table->string('student_branch')->nullable();
            $table->string('student_year')->nullable(); // 1st, 2nd, 3rd, 4th

            // Certificate details
            $table->string('achievement')->nullable(); // e.g., "1st Prize", "Participation"
            $table->text('description')->nullable(); // Custom text for the cert
            $table->date('issued_date');

            // File
            $table->string('pdf_path')->nullable();
            $table->string('qr_code_path')->nullable();

            // Status
            $table->enum('status', ['issued', 'revoked'])->default('issued');
            $table->text('revoke_reason')->nullable();

            // Email tracking
            $table->boolean('email_sent')->default(false);
            $table->timestamp('email_sent_at')->nullable();

            $table->timestamps();

            $table->index('enrollment_number');
            $table->index('certificate_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
