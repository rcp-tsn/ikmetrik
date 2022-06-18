<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeclarationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('declarations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sgk_company_id')->unsigned();
            $table->foreign('sgk_company_id')->references('id')->on('sgk_companies');
            $table->date('declarations_date'); // Belge Tarihi
            $table->bigInteger('law'); // Kanun
            $table->string('genus'); // Belge Mahiyeti
            $table->string('document_type'); // Belge Türü
            $table->string('data')->nullable(); // Bilgi Data
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
        Schema::dropIfExists('declarations');
    }
}
