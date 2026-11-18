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
        Schema::create('shopping_carts', function (Blueprint $table) {
            $table->id();
            $table->string('cart_id');
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');
            $table->string('size')->nullable();
            $table->decimal('price', 10, 2);
            $table->boolean('is_selected')->default(false);
            $table->timestamps();

            $table->index('cart_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopping_carts');
    }
};
