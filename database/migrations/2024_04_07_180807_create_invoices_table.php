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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->nullable();

            $table->string('invoice_no')->nullable();
            $table->string('invoice_category')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('phno')->nullable();
            $table->string('address')->nullable();
            $table->string('total')->nullable();
            $table->string('status')->nullable();
            $table->string('discount_total')->nullable();
            $table->string('sale_price_category')->nullable();
            $table->string('quote_no')->nullable();
            $table->string('invoice_date')->nullable();
            $table->string('overdue_date')->nullable();
            $table->string('quote_date')->nullable();
            $table->string('type')->nullable();
            $table->string('remark')->nullable();
            $table->string('balance_due')->nullable();
            $table->string('net_total')->nullable();
            $table->string('deposit')->nullable();
            $table->string('remain_balance')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('location')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
