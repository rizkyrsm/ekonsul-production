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
            $table->string('tempat_lahir')->nullable();
            $table->string('status_pernikahan')->nullable();
            $table->string('agama')->nullable();
        });
    }

    public function down()
    {
        Schema::table('detail_users', function (Blueprint $table) {
            $table->dropColumn(['tempat_lahir', 'status_pernikahan', 'agama']);
        });
    }
};
