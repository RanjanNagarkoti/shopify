<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopifyProductVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_product_variations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('session_id');
            $table->string('shopify_product_id', 30)->nullable()->index();
            $table->string('shopify_variation_id', 30)->nullable()->index();
            $table->string('title')->nullable();
            $table->float('price')->nullable();
            $table->string('sku')->nullable();
            $table->string('inventory_policy')->nullable();
            $table->json('variation_options')->nullable();
            $table->string('image_id')->nullable();
            $table->bigInteger('inventory_item_id')->nullable();
            $table->bigInteger('inventory_quantity')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('shopify_products')->onDelete('cascade');
            $table->foreign('session_id')->references('id')->on('sessions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shopify_product_variations');
    }
}
