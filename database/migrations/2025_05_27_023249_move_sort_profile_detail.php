<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        DB::statement("ALTER TABLE detail_users MODIFY COLUMN tempat_lahir VARCHAR(255) NULL AFTER tgl_lahir");
        DB::statement("ALTER TABLE detail_users MODIFY COLUMN status_pernikahan VARCHAR(50) NULL AFTER jenis_kelamin");
        DB::statement("ALTER TABLE detail_users MODIFY COLUMN agama VARCHAR(50) NULL AFTER status_pernikahan");
    }

    public function down()
    {
        // Jika perlu, bisa dikembalikan ke posisi sebelumnya:
        DB::statement("ALTER TABLE detail_users MODIFY COLUMN tempat_lahir VARCHAR(255) NULL AFTER nik");
        DB::statement("ALTER TABLE detail_users MODIFY COLUMN status_pernikahan VARCHAR(50) NULL AFTER status_online");
        DB::statement("ALTER TABLE detail_users MODIFY COLUMN agama VARCHAR(50) NULL AFTER status_pernikahan");
    }
};
