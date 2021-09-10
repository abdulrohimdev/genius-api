<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('app_code')->unique();
            $table->string('app_name');
            $table->string('app_description')->nullable();
            $table->string('app_route_frontend_web')->nullable();
            $table->string('app_route_frontend_mobile')->nullable();
            $table->string('app_icon_class')->nullable();
            $table->string('app_icon_image')->nullable();
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
        Schema::dropIfExists('applications');
    }
}
