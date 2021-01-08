<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributorProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributor_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distributor_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->integer('opening_stock');
            $table->integer('already_received')->nullable();
            $table->integer('stock_in_transit')->nullable();
            $table->integer('delivery_done')->nullable();
            $table->integer('in_delivery_van')->nullable();
            $table->integer('physical_stock');
            $table->date('pkg_date')->nullable();
            $table->boolean('declared')->default(false);
            $table->dateTime('declare_time')->nullable();
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
        Schema::dropIfExists('distributor_products');
    }
}
