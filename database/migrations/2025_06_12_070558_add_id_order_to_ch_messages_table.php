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
        Schema::table('ch_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('id_order')->nullable()->after('id');

            
            // Foreign key constraints (optional tapi disarankan)
            $table->foreign('id_order')->references('id_order')->on('orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('ch_messages', function (Blueprint $table) {
            $table->dropColumn('id_order');
        });
    }
};
