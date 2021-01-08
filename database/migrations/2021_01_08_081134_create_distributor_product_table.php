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
    protected $table = 'distributor_products';
    public function up()
    {

        Schema::create('distributor_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distributor_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->integer('opening_stock');
            $table->integer('already_received')->default(0);
            $table->integer('stock_in_transit')->default(0);
            $table->integer('delivery_done')->default(0);
            $table->integer('in_delivery_van')->default(0);
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
