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
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel transaksis
        $table->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade');
        // Menghubungkan ke tabel products
        $table->foreignId('product_id')->constrained('products');
        
        $table->integer('kuantitas');
        $table->integer('harga_satuan'); // Mencatat harga saat barang dibeli (antisipasi jika harga naik/turun nantinya)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_details');
    }
};
