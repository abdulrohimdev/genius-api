<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalDocumentStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_document_status', function (Blueprint $table) {
            $table->id();
            $table->string('doc_number',50)->unique();
            $table->string('doctype',50);
            $table->enum('status',['NEED APPROVAL','APPROVED','REJECTED']);
            $table->string('create_by');
            $table->string('approved_by');
            $table->string('approval_note');
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
        Schema::dropIfExists('approval_document_status');
    }
}
