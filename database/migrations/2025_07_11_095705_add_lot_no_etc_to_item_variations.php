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
        Schema::table('item_variations', function (Blueprint $table) {
            //
            $table->string('lot_no')->nullable();
            $table->string('arrival_date')->nullable();
            $table->string('muf_date')->nullable();
            $table->string('estd_test')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->string('distributor')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_variations', function (Blueprint $table) {
            //
        });
    }
};
