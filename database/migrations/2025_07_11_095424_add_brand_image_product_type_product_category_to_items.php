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
        Schema::table('items', function (Blueprint $table) {
            //
            $table->string('brand')->nullable();
            $table->string('image')->nullable();
            $table->string('product_type')->nullable();
            $table->string('product_category')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            //
            $table->dropColumn('brand');
            $table->dropColumn('image');
            $table->dropColumn('product_type');
            $table->dropColumn('product_category');
        });
    }
};
