<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('t_penilaian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_order');
            $table->unsignedBigInteger('id_konselor');
            $table->tinyInteger('nilai'); // 1 sampai 5
            $table->timestamps();

            $table->foreign('id_order')->references('id_order')->on('orders')->onDelete('cascade');
            $table->foreign('id_konselor')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_penilaian');
    }
};
