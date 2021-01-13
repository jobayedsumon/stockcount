<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->string('pkg_date');
            $table->integer('opening_stock');
            $table->integer('already_received')->nullable()->default(0);
            $table->integer('stock_in_transit')->nullable()->default(0);
            $table->integer('delivery_done')->nullable()->default(0);
            $table->integer('in_delivery_van')->nullable()->default(0);
            $table->integer('physical_stock');
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
        Schema::dropIfExists('product_stocks');
    }
}
