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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'processing_date')) {
                $table->timestamp('processing_date')->nullable();
            }
            if (!Schema::hasColumn('orders', 'shipped_date')) {
                $table->timestamp('shipped_date')->nullable();
            }
            if (!Schema::hasColumn('orders', 'delivered_date')) {
                $table->timestamp('delivered_date')->nullable();
            }
            if (!Schema::hasColumn('orders', 'canceled_date')) {
                $table->timestamp('canceled_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['processing_date', 'shipped_date', 'delivered_date', 'canceled_date']);
        });
    }
};
