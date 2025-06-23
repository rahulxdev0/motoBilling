<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('item_code')->unique();
            $table->string('sku')->unique(); 
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('partie_id')->constrained();
            $table->decimal('purchase_price', 10, 2); // Cost price
            $table->decimal('selling_price', 10, 2);
            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock_threshold')->default(5);
            $table->string('unit')->default('pcs');
            $table->timestamps();
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
