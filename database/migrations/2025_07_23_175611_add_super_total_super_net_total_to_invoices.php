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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('super_discount')->nullable();

            $table->string('super_total')->nullable();
            $table->string('super_net_total')->nullable();
            $table->string('super_deposit')->nullable();
            $table->string('super_remain_balance')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
            $table->dropColumn('super_discount');
            $table->dropColumn('super_total');
            $table->dropColumn('super_net_total');
            $table->dropColumn('super_deposit');
            $table->dropColumn('super_remain_balance');
        });
    }
};
