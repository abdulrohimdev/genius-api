<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormCaseProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_case_problems', function (Blueprint $table) {
            $table->id();
            $table->integer('problem_id');
            $table->string('case_type');
            $table->string('case');
            $table->integer('quantity');
            $table->string('decision')->nullable();
            $table->string('note')->nullable();
            $table->binary('image')->nullable();
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
        Schema::dropIfExists('form_case_problems');
    }
}
