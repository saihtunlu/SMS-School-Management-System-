<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable();
            $table->string('create_date');
            $table->string('due_date');
            $table->string('from_name');
            $table->string('from_phone');
            $table->string('from_address');
            $table->string('from_email');
            $table->string('to_email');
            $table->string('to_name');
            $table->string('to_phone');
            $table->string('to_job');
            $table->string('student_name');
            $table->string('student_grade');
            $table->string('bank_name');
            $table->string('bank_holder_name');
            $table->string('bank_no');
            $table->string('logo');
            $table->boolean('sent')->default(0);
            $table->string('status')->default('Pending');
            $table->string('signature');
            $table->string('sub_total');
            $table->string('discount');
            $table->string('primary_total');
            $table->string('grand_total');
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
        Schema::dropIfExists('invoices');
    }
}
