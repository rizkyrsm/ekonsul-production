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
        Schema::create('diskons', function (Blueprint $table) {
            $table->id('id_diskon');
            $table->string('nama_diskon');
            $table->string('kode_voucher');
            $table->integer('jumlah_diskon_harga')->nullable();
            $table->integer('jumlah_diskon_persen')->nullable();
            $table->enum('status_aktiv', ['AKTIF', 'TIDAK AKTIF'])->default('AKTIF');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diskons');
    }
};
