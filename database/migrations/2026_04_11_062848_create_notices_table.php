<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('notices', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('content');
        $table->string('attachment_path')->nullable(); // Agar PDF upload karni ho
        
        // Filters (Null matlab sabke liye)
        $table->string('target_branch')->nullable(); 
        $table->integer('target_semester')->nullable();
        
        $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Kis admin/HOD ne banaya
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
