<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('mode');
            $table->string('theme_1');
            $table->string('theme_2');
            $table->string('theme_3');
            $table->string('semi_theme');
            $table->string('semi_dark');
            $table->string('dark');
            $table->string('input');
            $table->string('gray');
            $table->string('gray2');
            $table->string('main_bg');
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
        Schema::dropIfExists('themes');
    }
}
