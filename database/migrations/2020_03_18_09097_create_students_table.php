<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('profile_image');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('nick_name');
            $table->string('date_of_birth');
            $table->string('gender');
            $table->unsignedBigInteger('father_id')->nullable();
            $table->foreign('father_id')
                ->references('id')->on('fathers');
            $table->unsignedBigInteger('mother_id')->nullable();
            $table->foreign('mother_id')
                ->references('id')->on('mothers');
            $table->unsignedBigInteger('address_id')->nullable();
            $table->foreign('address_id')
                ->references('id')->on('addresses');
            $table->string('NRC')->nullable();
            $table->unsignedBigInteger('classroom_id')->nullable();
            $table->BigInteger('academic_year_id')->unsigned();
            $table->foreign('academic_year_id')
                ->references('id')->on('academic_years')
                ->onDelete('cascade');
            $table->string('emergency_contact_person');
            $table->string('emergency_contact_relationship');
            $table->string('emergency_contact_person_phone');
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
        Schema::dropIfExists('students');
    }
}
