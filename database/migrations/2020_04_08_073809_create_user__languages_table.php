<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user__languages', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('language_id')->unsigned();
            $table->foreign('language_id')
                ->references('id')->on('languages')
                ->onDelete('cascade');
            $table->BigInteger('employee_id')->unsigned();
            $table->foreign('employee_id')
                ->references('id')->on('employees')
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
        Schema::dropIfExists('user__languages');
    }
}
