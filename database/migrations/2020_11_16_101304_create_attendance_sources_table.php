<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_sources', function (Blueprint $table) {
            $table->id();
            $table->string('manual_upload_code')->nullable();
            $table->string('mobile_device_id')->nullable();
            $table->string('mobile_device_name')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('radius')->nullable();
            $table->string('location_mobile_absence')->nullable();
            $table->string('company_code',20);
            $table->string('employee_id',40);
            $table->date('date_scan');
            $table->string('time_scan');
            $table->enum('attendance_type',['manual_upload','finger_auto','mobile']);
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
        Schema::dropIfExists('attendance_sources');
    }
}
