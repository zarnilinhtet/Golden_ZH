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
            //
            $table->string('super_item_discount')->nullable()->after('item_discount');
            $table->string('super_ks_percent')->nullable()->after('super_item_discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sells', function (Blueprint $table) {
            //
            $table->dropColumn('super_item_discount');
            $table->dropColumn('super_ks_percent');
        });
    }
};
