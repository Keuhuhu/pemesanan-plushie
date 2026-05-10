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
        Schema::create('checkout_details', function (Blueprint $table) {
            $table->id();
            // Menyambungkan tabel ini ke tabel transaksis
            $table->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade'); 
            $table->string('first_name');
            $table->string('last_name');
            $table->text('address');
            $table->string('city');
            $table->string('postal_code');
            $table->timestamps();

            // Info Kartu Pembayaran (dibuat nullable berjaga-jaga jika ada metode bayar lain)
            $table->string('card_name')->nullable();
            $table->string('card_number')->nullable();
            $table->string('expiry_date')->nullable();
            $table->string('cvv')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout_details');
    }
};
