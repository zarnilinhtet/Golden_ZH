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
        Schema::create('item_kits', function (Blueprint $table) {
            $table->id();
            $table->string('item_id', 191)->nullable();
            $table->string('warehouse', 191)->nullable();
            // $table->string('item_kit_name', 191)->nullable();
            // $table->string('item_kit_description', 191)->nullable();
            // $table->string('item_kit_quantity', 191)->nullable();
            // $table->string('item_kit_retail_price', 191)->nullable();
            // $table->string('item_kit_wholesale_price', 191)->nullable();
            // $table->string('item_kit_unit', 191)->nullable();
            // $table->string('item_kit_amount', 191)->nullable();
            $table->string('item_name', 191)->nullable();
            $table->string('qty', 191)->nullable();
            $table->string('item_unit', 191)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_kits');
    }
};
