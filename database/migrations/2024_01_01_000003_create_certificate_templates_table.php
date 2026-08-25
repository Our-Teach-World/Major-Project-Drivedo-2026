<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Participation Certificate", "Winner Certificate"
            $table->string('type'); // participation, achievement, completion, winner
            $table->longText('html_content'); // HTML template with {{placeholders}}
            $table->string('background_image')->nullable();
            $table->string('border_style')->default('classic'); // classic, modern, minimal
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
    }
};
