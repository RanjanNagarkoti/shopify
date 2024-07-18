<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopifyCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id');
            $table->bigInteger('shopify_collection_id')->index();
            $table->string('handle');
            $table->string('title');
            $table->string('type');
            $table->bigInteger('product_count')->nullable();
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
        Schema::dropIfExists('shopify_collections');
    }
}
