<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_letters', function (Blueprint $table) {
            $table->id();
            $table->string('doc_number')->unique();
            $table->string('number_of_letter')->unique();
            $table->string('company_code');
            $table->string('department_code',10);
            $table->string('type_id',10);
            $table->string('category_id',10);
            $table->string('area_code',10);
            $table->string('title');
            $table->enum('confidential',['N','Y']);
            $table->string('upload_path_document')->nullable();
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
        Schema::dropIfExists('company_letters');
    }
}
