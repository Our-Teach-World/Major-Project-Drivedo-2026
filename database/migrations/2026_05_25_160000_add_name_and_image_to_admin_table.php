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
        Schema::table('admin', function (Blueprint $table) {
            if (!Schema::hasColumn('admin', 'name')) {
                $table->string('name', 255)->nullable()->after('username');
            }
            if (!Schema::hasColumn('admin', 'image_path')) {
                $table->string('image_path', 255)->nullable()->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin', function (Blueprint $table) {
            if (Schema::hasColumn('admin', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('admin', 'image_path')) {
                $table->dropColumn('image_path');
            }
        });
    }
};
