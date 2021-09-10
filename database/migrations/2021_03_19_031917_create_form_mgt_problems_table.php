<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormMgtProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('management_form_problems', function (Blueprint $table) {
            $table->id();
            $table->string('company_code');
            $table->string('management_area');
            $table->string('location');
            $table->string('process');
            $table->string('type');
            $table->string('product');
            $table->string('line')->nullable();
            $table->string('create_by');
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
        Schema::dropIfExists('management_form_problems');
    }
}
