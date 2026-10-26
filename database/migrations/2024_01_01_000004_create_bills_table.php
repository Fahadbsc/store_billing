<?php
// backend/database/migrations/2024_01_01_000004_create_bills_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number', 50)->unique();
            $table->foreignId('cashier_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name')->default('Walk-in Customer');
            $table->string('customer_phone', 20)->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->enum('discount_type', ['percent', 'flat'])->default('percent');
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('payment_mode', ['cash', 'card', 'upi'])->default('cash');
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->decimal('change_amount', 10, 2)->default(0);
            $table->enum('status', ['paid', 'void'])->default('paid');
            $table->timestamps();

            $table->index('created_at');
            $table->index('status');
            $table->index('cashier_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
