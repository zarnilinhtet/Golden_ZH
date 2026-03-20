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
        Schema::table('item_kits', function (Blueprint $table) {
            //
            $table->string('item_kit_name')->after('warehouse')->nullable();
            $table->string('retail_price')->after('item_kit_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_kits', function (Blueprint $table) {
            //
            $table->dropColumn('item_kit_name');
            $table->dropColumn('retail_price');
        });
    }
};
