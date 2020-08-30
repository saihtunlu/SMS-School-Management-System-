<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('profile_image')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('nick_name');
            $table->string('email');
            $table->string('date_of_birth')->nullable();
            $table->string('gender');
            $table->unsignedBigInteger('address_id')->nullable();
            $table->foreign('address_id')
                ->references('id')->on('addresses');
            $table->string('NRC');
            $table->string('position');
            $table->string('phone');
            $table->unsignedBigInteger('classroom_id')->nullable();
            $table->string('medical_history')->nullable();
            $table->string('bank_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('emergency_contact_person')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('emergency_contact_person_phone')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')->on('users');
            $table->softDeletes();
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
        Schema::dropIfExists('employees');
    }
}
