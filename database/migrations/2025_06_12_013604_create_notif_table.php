<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifs', function (Blueprint $table) {
            $table->id();
            $table->string('keterangan', 255)->nullable();
            $table->unsignedBigInteger('id_order');
            $table->enum('role', ['ADMIN', 'CABANG', 'KONSELOR', 'USER']);
            $table->unsignedBigInteger('id_penerima');
            $table->enum('status', ['terkirim', 'terbaca'])->default('terkirim');
            $table->timestamps();

            // Foreign key constraints (optional tapi disarankan)
            $table->foreign('id_order')->references('id_order')->on('orders')->onDelete('cascade');
            $table->foreign('id_penerima')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifs');
    }
};
