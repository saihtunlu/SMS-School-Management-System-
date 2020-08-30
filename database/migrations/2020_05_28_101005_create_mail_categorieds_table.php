<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailCategoriedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_categorieds', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('mail_id')->unsigned();
            $table->foreign('mail_id')
                ->references('id')->on('mails')
                ->onDelete('cascade');
            $table->BigInteger('mail_category_id')->unsigned();
            $table->foreign('mail_category_id')
                ->references('id')->on('mail_categories')
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
        Schema::dropIfExists('mail_categorieds');
    }
}
