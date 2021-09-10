<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeOrgAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_org_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id',30);
            $table->string('company_code',20);
            $table->string('area_code',20);
            $table->string('subarea_code',20);
            $table->string('emp_group_code',20);
            $table->string('unit_code',20);
            $table->string('job_code',20);
            $table->string('position_code',20);
            $table->string('level_code',20);
            $table->string('grade_code',20);
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
        Schema::dropIfExists('employee_org_assignments');
    }
}
