<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->string('sku')->unique();
            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->integer('stock_quantity');
            $table->integer('low_stock_threshold')->default(10);
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->json('images')->nullable();
            $table->json('sizes')->nullable(); // For uniforms: ["XS", "S", "M", "L", "XL"]
            $table->json('colors')->nullable(); // ["Navy Blue", "White", "Maroon"]
            $table->enum('status', ['active', 'inactive', 'out_of_stock'])->default('active');
            $table->decimal('weight', 8, 2)->nullable();
            $table->json('specifications')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('view_count')->default(0);
            $table->timestamps();
            
            $table->index(['category_id', 'status']);
            $table->index(['is_featured', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};