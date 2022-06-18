<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulePacketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_packet', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('module_id')->unsigned();
            $table->foreign('module_id')->references('id')->on('modules');
            $table->bigInteger('packet_id')->unsigned();
            $table->foreign('packet_id')->references('id')->on('packets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_packet');
    }
}
