<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceShiftNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_shift_names', function (Blueprint $table) {
            $table->id();
            $table->string('shift_name_code',30)->unique();
            $table->string('shift_name_desc');
            $table->string('time_in');
            $table->string('time_out');
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
        Schema::dropIfExists('attendance_shift_names');
    }
}
