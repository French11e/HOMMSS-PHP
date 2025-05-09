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
        Schema::table('users', function (Blueprint $table) {
            // Check if profile_picture column doesn't exist before adding it
            if (!Schema::hasColumn('users', 'profile_picture')) {
                $table->string('profile_picture')->nullable()->after('mobile');
            }

            // Check if bio column doesn't exist before adding it
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('profile_picture');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Only drop columns if they exist
            if (Schema::hasColumn('users', 'profile_picture')) {
                $table->dropColumn('profile_picture');
            }

            if (Schema::hasColumn('users', 'bio')) {
                $table->dropColumn('bio');
            }
        });
    }
};
