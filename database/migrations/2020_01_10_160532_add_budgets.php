<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBudgets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('categories', function (Blueprint $table) {
            $table->decimal('budget_expense', 8 , 2)->default(0);
            $table->decimal('budget_income', 8 , 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('budget_expense');
            $table->dropColumn('budget_income');
        });
    }
}
