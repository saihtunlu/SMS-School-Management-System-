<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('create')->default(0);
            $table->boolean('update')->default(0);
            $table->boolean('read')->default(0);
            $table->boolean('delete')->default(0);
            $table->BigInteger('role_id')->unsigned();
            $table->foreign('role_id')
                ->references('id')->on('roles')
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
        Schema::dropIfExists('default_permissions');
    }
}
