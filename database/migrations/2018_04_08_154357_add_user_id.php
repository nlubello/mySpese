<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('categories', function (Blueprint $table) {
        $table->integer('user_id')->nullable();
      });
      Schema::table('expenses', function (Blueprint $table) {
        $table->integer('user_id');
      });
      Schema::table('periodics', function (Blueprint $table) {
        $table->integer('user_id');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('categories', function (Blueprint $table) {
        $table->dropColumn('user_id');
      });
      Schema::table('expenses', function (Blueprint $table) {
        $table->dropColumn('user_id');
      });
      Schema::table('periodics', function (Blueprint $table) {
        $table->dropColumn('user_id');
      });
    }
}
