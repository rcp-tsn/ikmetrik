<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeclarationServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('declaration_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('declaration_id')->unsigned();
            $table->foreign('declaration_id')->references('id')->on('declarations');
            $table->bigInteger('tck');
            $table->bigInteger('sg_sicil_no')->nullable();
            $table->string('isim');
            $table->string('soyisim');
            $table->decimal('ucret_tl', 10, 2)->nullable();
            $table->decimal('ikramiye_tl', 10, 2)->nullable();
            $table->integer('gun')->nullable();
            $table->date('job_start')->nullable();
            $table->string('meslek_kod')->nullable();
            $table->integer('request')->default(false);
            $table->softDeletes();
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
        Schema::dropIfExists('declaration_services');
    }
}
