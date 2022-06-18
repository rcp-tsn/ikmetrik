<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('lft')->nullable();
            $table->integer('rgt')->nullable();
            $table->integer('depth')->nullable();
            $table->integer('company_id')->nullable();
            $table->unsignedInteger('owner_id')->nullable();
            $table->string('logo')->nullable();
            $table->string('address')->nullable();
            $table->string('telephone')->nullable();
            $table->decimal('balance', 10, 2)->default(0.00);
            $table->enum('type', ['parent', 'location', 'sub_company'])->default('parent');
            $table->integer('performance_term')->default(9);
            $table->string('performance_period', 20)->nullable();
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
        Schema::dropIfExists('companies');
    }
}
