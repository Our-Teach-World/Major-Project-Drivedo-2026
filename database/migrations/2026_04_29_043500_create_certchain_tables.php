<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certchain_events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('event_type'); // Workshop, Seminar, Competition, etc.
            $table->date('event_date');
            $table->date('event_end_date')->nullable();
            $table->string('venue')->nullable();
            $table->string('department')->nullable();
            $table->unsignedBigInteger('created_by'); // Can be Admin ID or Teacher ID
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamps();
        });

        Schema::create('certchain_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Participation Certificate", "Winner Certificate"
            $table->string('type'); // participation, achievement, completion, winner
            $table->longText('html_content'); // HTML template with {{placeholders}}
            $table->string('background_image')->nullable();
            $table->string('border_style')->default('classic'); // classic, modern, minimal
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });

        Schema::create('certchain_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_id')->unique(); // e.g. CERT-2024-ABC12345
            $table->foreignId('event_id')->constrained('certchain_events')->onDelete('cascade');
            $table->foreignId('template_id')->constrained('certchain_templates')->onDelete('cascade');
            $table->unsignedBigInteger('issued_by');

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

        Schema::create('certchain_blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('block_index'); // Sequential block number
            $table->foreignId('certificate_id')->constrained('certchain_certificates')->onDelete('cascade');
            $table->string('certificate_uid'); // Duplicate of certificate_id for integrity

            // Blockchain core fields
            $table->string('previous_hash', 64); // SHA-256 of previous block
            $table->string('data_hash', 64);     // SHA-256 of certificate data
            $table->string('block_hash', 64);    // SHA-256 of (index + prev_hash + data_hash + timestamp)

            // The actual data that was hashed (for verification)
            $table->json('block_data'); // Snapshot of certificate data at time of issue

            $table->timestamp('mined_at'); // When this block was created

            $table->unique('block_index');
            $table->unique('block_hash');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certchain_blocks');
        Schema::dropIfExists('certchain_certificates');
        Schema::dropIfExists('certchain_templates');
        Schema::dropIfExists('certchain_events');
    }
};
