<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalAttributeCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_attribute_cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('additional_attribute_id')->nullable()->constrained('additional_package_attributes')->onDelete('cascade');
            $table->foreignId('cart_id')->nullable()->constrained('carts')->onDelete('cascade');
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
        Schema::dropIfExists('additional_attribute_cart');
    }
}
