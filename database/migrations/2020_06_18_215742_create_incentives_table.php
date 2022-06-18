<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncentivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incentives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sgk_company_id');
            $table->bigInteger('tck');
            $table->integer('law'); // Kanun
            $table->integer('incentive_period')->nullable(); // Teşvik Süresi
            $table->date('start'); // Başlangıç
            $table->date('finish'); // Bitiş
            $table->integer('min_personel'); // İlave Olunacak Sayı
            $table->decimal('unincentive_amount', 10, 2)->nullable(); // Teşviksiz Ödenecek Tutar
            $table->decimal('incentive_amount', 10, 2)->nullable(); // Teşvik Tutarı
            $table->decimal('after_incentive', 10, 2)->nullable(); // Teşvik Sonrası
            $table->integer('filter_status')->default(false);
            $table->date('job_start')->nullable();
            $table->date('job_finish')->nullable();
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
        Schema::dropIfExists('incentives');
    }
}
