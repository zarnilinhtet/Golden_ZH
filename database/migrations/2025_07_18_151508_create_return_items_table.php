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
        Schema::create('return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_return_id');
            $table->foreignId('invoice_id');
            $table->string('product_name')->nullable();
            $table->foreignId('variation_id')->nullable();
            $table->foreignId('item_id')->nullable();
            $table->foreignId('customer_id')->nullable();
            $table->string('description')->nullable();
            $table->string('product_qty')->nullable();
            $table->string('product_price')->nullable();
            $table->string('retail_price')->nullable();
            $table->string('warehouse')->nullable();
            $table->string('discount')->nullable();
            $table->string('item_discount')->nullable();
            $table->string('unit')->nullable();
            $table->string('exp_date')->nullable();
            $table->string('part_number')->nullable();
            $table->string('ks_percent')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_items');
    }
};
