<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteTableAccessCodeFromOrdersTableAndAddColumnAccessCodeToConfessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('confessions', function (Blueprint $table) {
            $table->mediumText('access_code')->after("public");
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('access_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->mediumText('access_code');
        });
        Schema::table('confessions', function (Blueprint $table) {
            $table->dropColumn('access_code');
        });
    }
}
