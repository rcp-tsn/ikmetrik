<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovedIncentivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approved_incentives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sgk_company_id');
            $table->date('accrual'); // Tahakkuk Yıl/Ay
            $table->date('service'); // Hizmet Yıl/Ay
            $table->integer('document_type'); // Belge Türü
            $table->string('genus'); // Belge Mahiyeti
            $table->integer('law_no'); // Kanun No
            $table->integer('total_staff'); // Toplam Çalışan Sayısı
            $table->integer('total_day')->nullable(); // Toplam Gün Sayısı
            $table->string('total_salary'); // Toplam Pek Tutar
            $table->string('document_no'); // Belge Numarası (PDF Oluşturmak İçin)
            $table->integer('pdf_parse')->default(false);
            $table->integer('pdf_download')->default(false);
            $table->integer('staff_records')->default(false);
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
        Schema::dropIfExists('approved_incentives');
    }
}
