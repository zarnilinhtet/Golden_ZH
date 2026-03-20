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
            $table->string('variation_id')->nullable()->after('item_id');
            $table->string('product_name')->nullable()->after('variation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_kits', function (Blueprint $table) {
            $table->dropColumn('variation_id');
            $table->dropColumn('product_name');
        });
    }
};
