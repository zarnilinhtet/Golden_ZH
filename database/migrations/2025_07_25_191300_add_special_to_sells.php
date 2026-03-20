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
            $table->string('special_price')->nullable()->after('retail_price');
            $table->string('super')->nullable()->after('super_ks_percent');
            $table->string('super_discount')->nullable()->after('super');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sells', function (Blueprint $table) {
            //
            $table->dropColumn('special_price');
            $table->dropColumn('super');
            $table->dropColumn('super_discount');
        });
    }
};
