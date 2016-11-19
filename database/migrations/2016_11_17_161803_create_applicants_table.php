<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('applicants', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('applicationId');
            $table->string('firstName');
            $table->string('middleName')->nullable();
            $table->string('lastName');
            $table->string('gender');
            $table->date('dateOfBirth')->nullable();
            $table->string('maritalStatus');
            $table->string('race');
            $table->string('ssn');
            $table->string('email');
            $table->string('addressLine1', 100);
            $table->string('addressLine2', 100)->nullable();
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('zipCode');
            $table->string('location')->nullable();
            $table->string('phoneNumber');
            $table->string('bankName')->nullable();
            $table->string('accountType')->nullable();
            $table->string('routingNumber')->nullable();
            $table->string('accountNumber')->nullable();
            $table->string('employeeNumber')->nullable();
            $table->string('supervisor')->nullable();
            $table->date('hireDate')->nullable();
            $table->string('fullOrPartTime')->nullable();
            $table->string('compType')->nulable();
            $table->decimal('payRate', 10, 2);
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicants');
    }
}
