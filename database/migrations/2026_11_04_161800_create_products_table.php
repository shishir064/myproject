
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable()->unique();
            $table->longText('description')->nullable();

            // Relationships
            $table->foreignId('category_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('brand_id')
                ->constrained()
                ->restrictOnDelete();

            // Product Status
            $table->boolean('status')->default(true);

            // Pricing Information
            $table->decimal('purchase_price', 12, 2)->nullable();
            $table->decimal('price', 12, 2);

            // Discount Information
            // percentage or fixed
            $table->enum('discount_type', ['percentage', 'fixed'])
                ->nullable();

            $table->decimal('discount_value', 12, 2)->default(0);

            // Inventory Information
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('low_stock_threshold')->default(5);

            // Shipping Information
            $table->decimal('weight', 8, 2)->nullable();

            // Timestamps & Soft Deletes
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index(['category_id', 'status']);
            $table->index(['brand_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
