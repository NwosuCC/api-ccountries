<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnContinentToContinentId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('countries', function (Blueprint $table) {
          $table->dropColumn('continent');
        });

        Schema::table('countries', function (Blueprint $table) {
          $table->integer('continent_id')->after('name');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      /*Schema::table('countries', function (Blueprint $table) {
        $table->dropColumn('continent_id');
      });

      Schema::table('countries', function (Blueprint $table) {
        $table->string('continent')->after('name');
      });*/
    }
}
