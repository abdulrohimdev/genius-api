<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_memberships', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id',30);
            $table->string('membership_code',30);
            $table->string('membership_name')->nullable();
            $table->date('begin_effective');
            $table->date('last_effective');
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
        Schema::dropIfExists('employee_memberships');
    }
}
