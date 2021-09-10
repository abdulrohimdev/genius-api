<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_flows', function (Blueprint $table) {
            $table->id();
            $table->string('company_code',5);
            $table->string('doctype',50);
            $table->enum('approver_type',['username','position']);
            $table->string('approver',30);
            $table->integer('approver_level');
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
        Schema::dropIfExists('approval_flows');
    }
}
