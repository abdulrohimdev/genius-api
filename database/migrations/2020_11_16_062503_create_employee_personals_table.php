<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeePersonalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_personals', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id',30)->unique();
            $table->string('sap_number',30);
            $table->binary('photos');
            $table->string('fullname',70);
            $table->string('birthplace',50);
            $table->date('birthdate');
            $table->enum('gender',['Male','Female']);
            $table->enum('marital_status',['Single','Married','Divorced']);
            $table->enum('religion',['Islam','Katholik','Protestan','Budha','Hindu','Kong Hu Cu','Others']);
            $table->enum('education',['NSD','SD','SMP','SMA','SMK','D1','D2','D3','D4','S1','S2','S3']);
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
        Schema::dropIfExists('employee_personals');
    }
}
