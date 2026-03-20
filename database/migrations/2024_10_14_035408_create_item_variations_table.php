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
        Schema::create('item_variations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('expired_date')->nullable();
            $table->string('descriptions')->nullable();
            $table->string('product_code')->nullable();
            $table->string('variations_barcode')->nullable();
            $table->string('quantity')->nullable();
            $table->string('reorder_level_stock')->nullable();
            $table->string('unit1')->nullable();
            $table->string('unit2')->nullable();
            $table->string('unit3')->nullable();
            $table->string('name1')->nullable();
            $table->string('name2')->nullable();
            $table->string('name3')->nullable();
            $table->string('price1')->nullable();
            $table->string('price2')->nullable();
            $table->string('price3')->nullable();
            $table->string('retail1')->nullable();
            $table->string('retail2')->nullable();
            $table->string('retail3')->nullable();
            $table->string('wholesale1')->nullable();
            $table->string('wholesale2')->nullable();
            $table->string('wholesale3')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_variations');
    }
};
