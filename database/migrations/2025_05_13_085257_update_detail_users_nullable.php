<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('detail_users', function (Blueprint $table) {
            // Hapus index unique pada kolom nik
            $table->dropUnique('detail_users_nik_unique');
        });
    }

    public function down()
    {
        Schema::table('detail_users', function (Blueprint $table) {
            // Tambahkan kembali indeks unique jika rollback
            $table->unique('nik');
        });
    }
};
