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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->nullable();
            $table->string('item_name')->nullable();
            $table->string('barcode')->nullable();
            $table->string('descriptions')->nullable();
            $table->string('expired_date')->nullable();
            $table->string('category')->nullable();
            $table->string('price')->nullable();
            $table->string('market')->nullable();
            $table->string('reorder_level_stock')->nullable();
            $table->string('quantity')->nullable();
            $table->string('item_unit')->nullable();
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
            $table->string('buy_price')->nullable();
            $table->string('parent_id')->default('0');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
