<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'branch')) {
                $table->string('branch')->nullable()->after('designation');
            }
            if (!Schema::hasColumn('users', 'alumni_details')) {
                $table->text('alumni_details')->nullable()->after('branch');
            }
            if (!Schema::hasColumn('users', 'company')) {
                $table->string('company')->nullable()->after('alumni_details');
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('company');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['branch', 'alumni_details', 'company', 'bio']);
        });
    }
};
