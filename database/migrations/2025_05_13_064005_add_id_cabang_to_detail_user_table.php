<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('detail_users', function (Blueprint $table) {
            // Menambahkan field id_cabang sebagai foreign key
            $table->unsignedBigInteger('id_cabang')->nullable()->after('id_user');
            $table->foreign('id_cabang')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('detail_users', function (Blueprint $table) {
            // Menghapus foreign key dan kolom
            $table->dropForeign(['id_cabang']);
            $table->dropColumn('id_cabang');
        });
    }
};
