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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->longText("content");
            $table->string("image");
            $table->enum("sale_type", ['retail', 'wholesale', 'bid']);
            $table->double("price", 10, 2, true);
            $table->bigInteger("category_id", false, true);
            $table->bigInteger("district_id", false, true);
            $table->bigInteger("user_id", false, true);
            $table->timestamps();
            //FORIGN KEYS
            $table->foreign("category_id")->on("product_categories")->references("id")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("district_id")->on("districts")->references("id")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("user_id")->on("users")->references("id")->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
