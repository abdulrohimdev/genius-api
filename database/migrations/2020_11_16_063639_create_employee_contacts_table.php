<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id',30);
            $table->string('whatsapp_number',20)->nullable();
            $table->string('mobile_number',20)->nullable();
            $table->string('linkedin_account')->nullable();
            $table->string('facebook_account')->nullable();
            $table->string('instagram_account')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->enum('type_contacts',['Primary','Secondary','Emergency']);
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
        Schema::dropIfExists('employee_contacts');
    }
}
