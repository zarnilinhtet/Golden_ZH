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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_id')->nullable();

            $table->string('invoice_no')->nullable();
            $table->string('item_no')->nullable();
            $table->string('invoice_category')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('phno')->nullable();
            $table->string('address')->nullable();
            $table->string('total')->nullable();
            $table->string('status')->nullable();
            $table->string('discount_total')->nullable();
            $table->string('quote_no')->nullable();
            $table->string('po_date')->nullable();
            $table->string('overdue_date')->nullable();
            $table->string('type')->nullable();
            $table->string('remark')->nullable();
            $table->string('balance_due')->nullable();
            $table->string('net_total')->nullable();
            $table->string('deposit')->nullable();
            $table->string('unit')->nullable();
            $table->string('exp_date')->nullable();
            $table->string('remain_balance')->nullable();
            $table->string('payment_method')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
