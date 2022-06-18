<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSgkCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sgk_companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->bigInteger('company_id')->nullable();
            $table->decimal('company_gain_ratio', 10, 2)->default(0);
            $table->bigInteger('registry_id')->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('login_status')->default(false);
            $table->string('company_username')->nullable();
            $table->string('company_usercode')->nullable();
            $table->string('company_syspassword')->nullable();
            $table->string('company_password')->nullable();

            $table->string('cookiev2')->nullable();
            $table->boolean('cookiev2_status')->default(false);

            $table->string('cookiev3')->nullable();
            $table->boolean('cookiev3_status')->default(false);
            $table->date('founded')->nullable();
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
        Schema::drop('sgk_companies');
    }
}
