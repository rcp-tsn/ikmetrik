<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePacketInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packet_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inventorytable_id');
            $table->string('inventorytable_type');
            $table->bigInteger('packet_id')->unsigned();
            $table->foreign('packet_id')->references('id')->on('packets')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packet_inventories');
    }
}
