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
            $table->id();
            $table->bigInteger("user_id", false, true);
            $table->double("total", 10, 2, true);
            $table->bigInteger("status_id", false, true);
            $table->bigInteger("address_id", false, true);
            $table->timestamps();
            //FORIGN KEYS
            $table->foreign("user_id")->on("users")->references("id")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("address_id")->on("user_addresses")->references("id")->cascadeOnDelete()->cascadeOnUpdate();
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
