<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncentiveServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incentive_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('approved_incentive_id');
            $table->bigInteger('tck');
            $table->string('isim');
            $table->string('soyisim');
            $table->string('kizlik_soyadi')->nullable();
            $table->decimal('ucret_tl', 10, 2)->nullable();
            $table->decimal('ikramiye_tl', 10, 2)->nullable();
            $table->integer('gun')->nullable();
            $table->integer('eksik_gun')->nullable();
            $table->date('job_start')->nullable();
            $table->date('job_finish')->nullable();
            $table->string('icn')->nullable();
            $table->integer('egn')->default(0);
            $table->string('meslek_kod')->default(0);
            $table->integer('history_request')->default(false); // Geçmişe Dönük Sorgulama
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
        Schema::dropIfExists('incentive_services');
    }
}
