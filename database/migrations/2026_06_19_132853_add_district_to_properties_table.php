<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('properties', 'district')) return;

        Schema::table('properties', function (Blueprint $table) {
            $table->string('district')->nullable()->after('city');
            $table->json('nearby_places')->nullable()->after('images');
            $table->string('video_url')->nullable()->after('nearby_places');
            $table->boolean('featured')->default(false)->after('status');
            $table->json('documents')->nullable()->after('featured');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['district', 'nearby_places', 'video_url', 'featured', 'documents']);
        });
    }
};
