<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->date('starting_at');
            $table->date('ending_at')->nullable();
            $table->integer('type')->default(0);
            $table->decimal('amount')->default(0);
            $table->string('period')->default('m');
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
        Schema::drop('periodics');
    }
}
