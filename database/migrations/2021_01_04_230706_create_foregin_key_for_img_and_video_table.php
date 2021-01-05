<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeginKeyForImgAndVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video', function (Blueprint $table) {
            $table->foreignId('confession_id')->nullable()->constrained('confessions')->onDelete('cascade');

        });
        Schema::table('img', function (Blueprint $table) {
            $table->foreignId('confession_id')->nullable()->constrained('confessions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('video', function (Blueprint $table) {
            $table->dropConstrainedForeignId('confession_id');
        });
        Schema::table('img', function (Blueprint $table) {
            $table->dropConstrainedForeignId('confession_id');
        });
    }
}
