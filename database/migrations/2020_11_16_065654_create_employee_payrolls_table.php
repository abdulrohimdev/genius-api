<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id',30);
            $table->string('payroll_area_code',10);
            $table->string('currency',10);
            $table->string('costcenter');
            $table->string('account_type');
            $table->enum('tax_type',['','Net','Gross','Non Deductable']);
            $table->enum('ptkp_status',['L0','L1','L2','L3','K0','K1','K2','K3']);
            $table->string('npwp')->nullable();
            $table->string('npwp_address')->nullable();
            $table->string('kpp_id');
            $table->string('bank_code');
            $table->string('payee',70);
            $table->string('bank_account',50);
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
        Schema::dropIfExists('employee_payrolls');
    }
}
