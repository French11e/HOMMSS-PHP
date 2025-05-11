<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Direct SQL approach to modify the enum values
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('ordered', 'processing', 'shipped', 'delivered', 'canceled') DEFAULT 'ordered'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('ordered', 'delivered', 'canceled') DEFAULT 'ordered'");
    }
};
