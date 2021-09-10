<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_status', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id',30);
            $table->date('enterprise_begin');
            $table->date('enterprise_last');
            $table->date('company_begin');
            $table->date('company_last');
            $table->date('permanent_date');
            $table->enum('status',['Active','Terminate']);
            $table->string('reason_terminate')->nullable();
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
        Schema::dropIfExists('employee_status');
    }
}
