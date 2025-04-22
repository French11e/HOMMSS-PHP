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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('name');                // Full name
            $table->string('phone');               // Mobile number
            $table->string('postal');              // Postal code
            $table->string('barangay');            // Barangay
            $table->string('city');                // City / Municipality
            $table->string('province');            // Province
            $table->string('region');              // Region
            $table->text('address');               // Street, Building, House No.
            $table->string('landmark')->nullable(); // Landmark (optional)

            $table->string('type')->default('home'); // Address type: home, work, etc.
            $table->boolean('isdefault')->default(false);

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
