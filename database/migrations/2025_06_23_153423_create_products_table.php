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
            $table->enum('type', ['service', 'product'])->default('product');
            $table->string('sku')->unique();
            $table->string('barcode')->unique()->nullable( );
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained();
            $table->string('brand')->nullable();
            $table->string('model_compatibility')->nullable();
            $table->decimal('purchase_price', 10, 2); 
            $table->decimal('selling_price', 10, 2);
            $table->decimal('mrp', 10, 2)->nullable();
            $table->string('hsn_code')->nullable();
            $table->decimal('gst_rate', 5, 2)->nullable(); 
            $table->integer('reorder_level')->default(10);
            $table->integer('stock_quantity')->default(0);
            $table->string('unit')->default('pcs');
            $table->enum('status', ['active', 'inactive'])->default('active');
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
