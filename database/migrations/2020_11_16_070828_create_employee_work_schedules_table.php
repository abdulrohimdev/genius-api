<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeWorkSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_work_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id',30);
            $table->string('workgroup_code',10);
            $table->string('sub_workgroup_code',10);
            $table->enum('absence_flag',['false','true']);
            $table->date('absence_date');
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
        Schema::dropIfExists('employee_work_schedules');
    }
}
