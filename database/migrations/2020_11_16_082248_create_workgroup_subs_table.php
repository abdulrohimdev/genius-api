<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkgroupSubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workgroup_subs', function (Blueprint $table) {
            $table->id();
            $table->string('workgroup_code_id',20);
            $table->string('sub_workgroup_code',20)->unique();
            $table->string('sub_workgroup_description')->nullable();
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
        Schema::dropIfExists('workgroup_subs');
    }
}
