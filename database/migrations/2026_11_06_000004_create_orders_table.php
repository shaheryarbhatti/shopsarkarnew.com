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
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('contributor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ref_code')->nullable();

            $table->string('customer_name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('country')->default('Pakistan');
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->text('address');
            $table->text('note')->nullable();

            $table->string('payment_method')->default('cod');
            $table->string('payment_status')->default('pending');
            $table->string('status')->default('pending'); // pending|processing|shipped|completed|cancelled

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('shipping_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->string('currency', 10)->default('PKR');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
