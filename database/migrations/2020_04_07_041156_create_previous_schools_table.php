<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreviousSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('previous_schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('started_date');
            $table->string('ended_date');
            $table->BigInteger('student_id')->unsigned();
            $table->foreign('student_id')
                ->references('id')->on('students')
                ->onDelete('cascade');
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
        Schema::dropIfExists('previous_schools');
    }
}
