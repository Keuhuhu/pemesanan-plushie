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
        Schema::table('products', function (Blueprint $table) {
            // Kita gunakan nullable() agar data produk lama yang sudah ada
            // di database tidak error karena dipaksa punya gambar.
            // after('stok') artinya kolom ini diletakkan setelah kolom stok.
            $table->string('gambar')->nullable()->after('stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Ini untuk menghapus kolom jika kita melakukan rollback
            $table->dropColumn('gambar');
        });
    }
};
