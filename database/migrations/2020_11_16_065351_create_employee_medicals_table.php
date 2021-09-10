<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeMedicalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_medicals', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id',30);
            $table->integer('height')->nullable();
            $table->integer('weight')->nullable();
            $table->enum('blood_type',['','A','O','B','AB']);
            $table->enum('rhesus',['','Positive','Negative']);
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
        Schema::dropIfExists('employee_medicals');
    }
}
