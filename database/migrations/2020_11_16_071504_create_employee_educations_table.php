<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeEducationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_educations', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id',30);
            $table->string('education_name');
            $table->string('degree')->nullbale();
            $table->string('major')->nullbale();
            $table->string('minor')->nullbale();
            $table->string('gpa')->nullbale();
            $table->date('graduation_date');
            $table->string('predicate')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('employee_educations');
    }
}
