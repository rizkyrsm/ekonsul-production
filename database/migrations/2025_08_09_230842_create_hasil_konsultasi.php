<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_konsultasi', function (Blueprint $table) {
            $table->id();
            // Foreign key ke tabel orders.id_order
            $table->unsignedBigInteger('id_order');
            $table->foreign('id_order')
                  ->references('id_order')
                  ->on('orders')
                  ->onDelete('cascade');

            $table->text('rangkuman')->nullable();
            $table->text('saran')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_konsultasi');
    }
};
