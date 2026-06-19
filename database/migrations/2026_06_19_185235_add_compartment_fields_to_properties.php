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
        Schema::table('properties', function (Blueprint $table) {
            $table->string('category')->default('standard'); // standard, moderne_simple, studio_moderne, etc.
            $table->integer('bedrooms')->default(0); // compartments/rooms
            $table->integer('bathrooms')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['category', 'bedrooms', 'bathrooms']);
        });
    }
};
