<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodoCategoriedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todo_categorieds', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('todo_id')->unsigned();
            $table->foreign('todo_id')
                ->references('id')->on('todos')
                ->onDelete('cascade');
            $table->BigInteger('todo_category_id')->unsigned();
            $table->foreign('todo_category_id')
                ->references('id')->on('todo_categories')
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
        Schema::dropIfExists('todo_categorieds');
    }
}
