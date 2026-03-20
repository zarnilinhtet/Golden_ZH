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
        Schema::table('transfer_histories', function (Blueprint $table) {
            $table->string('item_id')->nullable()->after('transfer_id');
            $table->string('variation_id')->nullable()->after('item_id');
            $table->string('product_name')->nullable()->after('item_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfer_histories', function (Blueprint $table) {
            $table->dropColumn('item_id');
            $table->dropColumn('variation_id');
            $table->dropColumn('product_name');
        });
    }
};
