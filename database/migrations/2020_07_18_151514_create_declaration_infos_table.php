<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeclarationInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('declaration_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tck');
            $table->string('isim')->nullable();
            $table->string('soyisim')->nullable();
            $table->string('ilk_soyadi')->nullable();
            $table->string('baba_adi')->nullable();
            $table->string('ana_adi')->nullable();
            $table->string('dogum_yeri')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('uyruk')->nullable();
            $table->string('education')->nullable();
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
        Schema::dropIfExists('declaration_infos');
    }
}
