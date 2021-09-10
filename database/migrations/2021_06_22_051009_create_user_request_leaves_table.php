<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRequestLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_request_leaves', function (Blueprint $table) {
            $table->id();
            $table->string('request_type');
            $table->string('request_hash_id');
            $table->string('number_unix');
            $table->string('request_user_id');
            $table->string('request_user_empid');
            $table->string('request_approval');
            $table->string('request_date');
            $table->string('request_time_leaving');
            $table->string('request_time_returning');
            $table->string('security_check_leave');
            $table->string('security_check_return');
            $table->text('request_reason');
            $table->enum('status',['Pending','Approved','Rejected']);
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
        Schema::dropIfExists('user_request_leaves');
    }
}
