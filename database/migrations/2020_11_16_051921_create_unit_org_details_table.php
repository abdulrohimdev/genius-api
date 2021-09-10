<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitOrgDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_org_details', function (Blueprint $table) {
            $table->id();
            $table->string('unit_organization_id');
            $table->string('company_code');
            $table->string('unit');
            $table->string('unit_name');
            $table->string('parent_unit');
            $table->string('cost_center');
            $table->string('type_of_unit');
            $table->date('effective');
            $table->date('last_date');
            $table->string('delete_flag');
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
        Schema::dropIfExists('unit_org_details');
    }
}
