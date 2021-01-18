<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderIdColumnToConfessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('confessions', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->after("id")->constrained('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('confessions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('order_id');
        });
    }
}
