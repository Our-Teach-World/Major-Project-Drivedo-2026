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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('author');
            $table->string('subject');
            $table->string('class'); // e.g., B.Tech - 2nd Year
            $table->string('college')->nullable();
            $table->decimal('price', 10, 2)->nullable(); // NULL for free books
            $table->longText('description');
            $table->string('condition'); // Like New, Good, Fair, Poor
            $table->longText('photo')->nullable(); // Base64 encoded image
            $table->longText('photos')->nullable();
            $table->string('status')->default('Available'); // Available, Sold, Removed
            $table->timestamps();
            
            // Indexes for common queries
            $table->index('user_id');
            $table->index('status');
            // FullText indexes are good but maybe engine dependent, keeping simple index
            // $table->fullText(['title', 'author', 'subject']);
        });

        Schema::create('book_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Ensure unique conversation between two users for a book
            $table->unique(['book_id', 'sender_id', 'receiver_id']);
            $table->index('sender_id');
            $table->index('receiver_id');
        });

        Schema::create('book_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('book_conversations')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->longText('text');
            $table->longText('image')->nullable(); // Base64 encoded image
            $table->timestamps();
            
            $table->index('conversation_id');
            $table->index('sender_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_messages');
        Schema::dropIfExists('book_conversations');
        Schema::dropIfExists('books');
    }
};
