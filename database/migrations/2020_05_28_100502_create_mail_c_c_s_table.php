<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailCCSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_c_c_s', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->BigInteger('mail_id')->unsigned();
            $table->foreign('mail_id')
                ->references('id')->on('mails')
                ->onDelete('cascade');
            $table->softDeletes();
            $table->BigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')
                ->references('id')->on('users')
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
        Schema::dropIfExists('mail_c_c_s');
    }
}
