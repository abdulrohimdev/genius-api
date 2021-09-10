<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceMobileRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_mobile_rules', function (Blueprint $table) {
            $table->id();
            $table->string('location_code')->unique();
            $table->string('location_name');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('radius_start');
            $table->string('radius_end');
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
        Schema::dropIfExists('attendance_mobile_rules');
    }
}
