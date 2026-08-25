<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blockchain_blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('block_index'); // Sequential block number
            $table->foreignId('certificate_id')->constrained('certificates')->onDelete('cascade');
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
        Schema::dropIfExists('blockchain_blocks');
    }
};
