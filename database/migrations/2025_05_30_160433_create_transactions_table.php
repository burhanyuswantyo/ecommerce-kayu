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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // Buyer information
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->string('name');
            $table->string('phone');

            // Shipping information
            $table->string('shipping_method');
            $table->text('address')->nullable();
            $table->foreignId('subdistrict_id')->nullable()->constrained('villages')->nullOnDelete();
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->string('courier')->nullable();
            $table->string('receipt_number')->nullable();

            // Transaction details
            $table->decimal('total_price', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->string('status');
            $table->string('payment_method')->nullable();
            $table->string('payment_status');
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_evidence')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
