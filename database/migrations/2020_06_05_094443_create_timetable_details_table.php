<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimetableDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetable_details', function (Blueprint $table) {
            $table->id();
            $table->string('from');
            $table->string('to');
            $table->string('title');
            $table->string('color');
            $table->string('day');
            $table->BigInteger('subject_id')->unsigned();
            $table->foreign('subject_id')
                ->references('id')->on('subjects')
                ->onDelete('cascade');
            $table->BigInteger('timetable_id')->unsigned();
            $table->foreign('timetable_id')
                ->references('id')->on('timetables')
                ->onDelete('cascade');
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
        Schema::dropIfExists('timetable_details');
    }
}
