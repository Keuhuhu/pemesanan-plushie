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
        Schema::table('checkout_details', function (Blueprint $table) {
            $table->string('card_name')->nullable()->after('postal_code');
            $table->string('card_number')->nullable()->after('card_name');
            $table->string('expiry_date')->nullable()->after('card_number');
            $table->string('cvv')->nullable()->after('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checkout_details', function (Blueprint $table) {
            //
        });
    }
};
