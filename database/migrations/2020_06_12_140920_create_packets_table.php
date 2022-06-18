<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePacketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->decimal('balance_to_added', 10, 2)->default(0.00)->nullable();
            $table->smallInteger('max_user_number')->default(1);
            $table->decimal('price', 10, 2);
            $table->smallInteger('period')->nullable()->comment('Ay olarak giriş yapınız');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('packets');
    }
}
