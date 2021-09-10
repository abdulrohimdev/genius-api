<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTrainingEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_training_events', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id',30);
            $table->string('title');
            $table->enum('event_type',['Training','Event']);
            $table->enum('certificate',['True','False']);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('passed',['true','false']);
            $table->string('grade')->nullable();
            $table->string('organizer')->nullable();
            $table->string('place')->nullable();
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
        Schema::dropIfExists('employee_training_events');
    }
}
