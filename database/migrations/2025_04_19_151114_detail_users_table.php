<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('detail_users', function (Blueprint $table) {
            $table->bigIncrements('id_detail');
            $table->unsignedBigInteger('id_user');

            $table->string('nama', 255);
            $table->string('nik', 20)->unique();
            $table->date('tgl_lahir');
            $table->string('alamat', 255);
            $table->string('no_tlp', 20);
            $table->string('email', 100)->unique();
            $table->enum('status_online', ['online', 'offline'])->default('offline');
            $table->enum('jenis_kelamin', ['LAKI-LAKI', 'PEREMPUAN','LAINYA']);
            $table->timestamps();

            // Foreign key ke tabel users
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_users');
    }
};
