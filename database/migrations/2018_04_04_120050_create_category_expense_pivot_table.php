<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryExpensePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_expense', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->integer('category_id')->unsigned()->index();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->integer('expense_id')->unsigned()->index();
            $table->foreign('expense_id')->references('id')->on('expenses')->onDelete('cascade');
            $table->primary(['category_id', 'expense_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('category_expense');
    }
}
