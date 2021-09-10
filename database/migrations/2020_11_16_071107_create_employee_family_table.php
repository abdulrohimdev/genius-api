<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeFamilyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_family', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id',30);
            $table->string('fullname',50);
            $table->enum('gender',['Male','Female']);
            $table->enum('relation_type',['Spouse','Child','Parent','Other family','Sister','Brother','Step Parent','Step Child']);
            $table->date('birthdate');
            $table->enum('marital_status',['Single','Married','Divorced']);
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
        Schema::dropIfExists('employee_family');
    }
}
