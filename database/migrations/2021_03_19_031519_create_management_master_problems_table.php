<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagementMasterProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('management_master_problems', function (Blueprint $table) {
            $table->id();
            $table->string('company_code');
            $table->string('management_area');
            $table->string('location');
            $table->string('process');
            $table->string('type');
            $table->string('product');
            $table->enum('case_type',['Visual','Dimensi','Others']);
            $table->string('problem');
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
        Schema::dropIfExists('management_master_problems');
    }
}
