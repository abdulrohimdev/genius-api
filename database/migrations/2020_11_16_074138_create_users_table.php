<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('api_key');
            $table->string('secret_key');
            $table->string('device_id')->nullable();
            $table->string('device_name')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('fullname',50);
            $table->string('email',50)->nullable();
            $table->string('phone',50)->nullable();
            $table->enum('locked',['Yes','No']);
            $table->string('company_code',50)->nullable();
            $table->string('employee_id',50)->nullable();
            $table->string('language',5);
            $table->enum('operational',['Y','N']);
            $table->binary('photo')->nullable();
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
        Schema::dropIfExists('users');
    }
}
