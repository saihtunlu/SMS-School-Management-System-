<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarCategoriedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_categorieds', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('calendarEvent_id')->unsigned();
            $table->foreign('calendarEvent_id')
                ->references('id')->on('calendar_events')
                ->onDelete('cascade');
            $table->BigInteger('calendar_category_id')->unsigned();
            $table->foreign('calendar_category_id')
                ->references('id')->on('calendar_categories')
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
        Schema::dropIfExists('calendar_categorieds');
    }
}
