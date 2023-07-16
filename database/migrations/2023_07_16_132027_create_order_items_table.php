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
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigInteger("product_id", false, true);
            $table->bigInteger("order_id", false, true);
            $table->integer("qty", false, true);
            $table->timestamps();
            //FORIGN KEYS
            $table->foreign("product_id")->on("products")->references("id")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign("order_id")->on("orders")->references("id")->cascadeOnUpdate()->cascadeOnDelete();
            $table->primary(["product_id", "order_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
