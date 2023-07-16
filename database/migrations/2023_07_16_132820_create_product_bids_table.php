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
        Schema::create('product_bids', function (Blueprint $table) {
            $table->bigInteger("product_id", false, true);
            $table->bigInteger("user_id", false, true);
            $table->double("amount", 10, 2, true);
            $table->timestamps();
            //FORIGN KEYS
            $table->foreign("product_id")->on("products")->references("id")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign("user_id")->on("users")->references("id")->cascadeOnUpdate()->cascadeOnDelete();
            $table->primary(["product_id", "user_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_bids');
    }
};
