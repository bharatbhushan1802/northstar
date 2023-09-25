<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('order_id');
            $table->integer('customer_id');
            $table->integer('inventory_id');
            $table->unsignedBigInteger('store_id');
            $table->integer('quantity');
            $table->tinyInteger('status')->comment('0: for pending, 1: for completed, 2: for failed');
            $table->timestamp("create_date");
            $table->timestamp("update_date");
            $table->foreign('store_id')->references('store_id')->on('stores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
