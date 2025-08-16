<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE orders MODIFY payment_status ENUM('LUNAS','BELUM BAYAR','SELESAI') NULL");
    }

    public function down()
    {
        // Kembalikan ke enum sebelumnya tanpa
        DB::statement("ALTER TABLE orders MODIFY payment_status ENUM('LUNAS','BELUM BAYAR') NULL");
    }
};
