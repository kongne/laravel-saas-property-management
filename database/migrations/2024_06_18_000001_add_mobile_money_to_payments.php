<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('cash','check','bank_transfer','credit_card','mobile_money','orange_money','mtn_money','other') DEFAULT NULL");

        Schema::table('payments', function (Blueprint $table) {
            $table->string('mobile_money_number', 30)->nullable()->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('mobile_money_number');
        });

        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('cash','check','bank_transfer','credit_card','mobile_money','other') DEFAULT NULL");
    }
};
