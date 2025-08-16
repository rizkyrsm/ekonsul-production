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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id_order');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_konselor')->constrained('users')->onDelete('cascade');
            $table->string('nama_layanan')->nullable();
            $table->string('voucher')->nullable();
            $table->integer('total');
            $table->enum('payment_status', ['LUNAS', 'BELUM BAYAR'])->default('BELUM BAYAR');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
