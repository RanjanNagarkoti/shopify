<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopifyProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id');
            $table->bigInteger('shopify_product_id')->nullable()->index();
            $table->string("title");
            $table->longText("description")->nullable();
            $table->string("vendor")->nullable();
            $table->string("product_type")->nullable();
            $table->string("handle")->nullable();
            $table->string("tags")->nullable();
            $table->string("status")->nullable();
            $table->string('image_src')->nullable();
            $table->string('shopify_url')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('shopify_products');
    }
}
