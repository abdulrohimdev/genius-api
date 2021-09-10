<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeRewardAndPunishmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_reward_and_punishments', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id',30);
            $table->enum('category',['punishment','reward']);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('description')->nullable();
            $table->string('doc_id')->nullable();
            $table->date('doc_date');
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
        Schema::dropIfExists('employee_reward_and_punishments');
    }
}
