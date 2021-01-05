<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->text('full_name')->nullable();
            $table->text('city');
            $table->text('country');
            $table->text('region');
            $table->text('street_address');
            $table->text('zip_code');
            $table->text('phone_number');
            $table->text('email');
            $table->text('order_remark')->nullable();
            $table->text('tax_id')->nullable();
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
        Schema::dropIfExists('billings');
    }
}
