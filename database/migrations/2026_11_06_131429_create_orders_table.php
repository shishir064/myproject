<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Customer Information
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('order_number')->unique();

            $table->string('customer_name');
            $table->string('email');
            $table->string('phone');

            // Shipping Address
            $table->string('address');
            $table->string('city');
            $table->string('Province')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->default('Nepal');

            // Order Amounts
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('shipping_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);

            // Payment Information
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');

            // Order Status
            $table->string('status')->default('pending');

            // Additional Information
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};