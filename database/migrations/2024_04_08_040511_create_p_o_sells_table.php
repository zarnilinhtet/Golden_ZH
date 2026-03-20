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
        Schema::create('p_o_sells', function (Blueprint $table) {
            $table->id();
            $table->string('invoiceid');

            $table->integer('supplier_id')->nullable();
            $table->string('description')->nullable();
            $table->string('product_qty')->nullable();
            $table->string('product_price')->nullable();
            $table->string('retail')->nullable();
            $table->string('wholesale')->nullable();
            $table->string('discount')->nullable();
            $table->string('warehouse')->nullable();
            $table->string('unit')->nullable();
            $table->string('exp_date')->nullable();
            $table->string('part_number')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_o_sells');
    }
};
