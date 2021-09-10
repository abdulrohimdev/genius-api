<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_lists', function (Blueprint $table) {
            $table->id();
            $table->string('empid');
            $table->string('user_id');
            $table->string('fullname');
            $table->string('divisi');
            $table->string('department');
            $table->string('company');
            $table->text('photo');
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
        Schema::dropIfExists('approval_lists');
    }
}
