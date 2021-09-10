<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_rules', function (Blueprint $table) {
            $table->id();
            $table->string('application')->nullable();
            $table->string('class_access')->nullable();
            $table->string('class_value')->nullable();
            $table->enum('is_query',['false','true']);
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
        Schema::dropIfExists('application_rules');
    }
}
