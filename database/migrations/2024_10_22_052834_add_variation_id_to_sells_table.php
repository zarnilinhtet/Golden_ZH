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
        Schema::table('sells', function (Blueprint $table) {
            $table->string('product_name')->nullable()->after('part_number');
            $table->string('variation_id')->nullable()->after('product_name');
            $table->string('item_id')->nullable()->after('variation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sells', function (Blueprint $table) {
            $table->dropColumn('product_name');
            $table->dropColumn('variation_id');
            $table->dropColumn('item_id');
        });
    }
};
