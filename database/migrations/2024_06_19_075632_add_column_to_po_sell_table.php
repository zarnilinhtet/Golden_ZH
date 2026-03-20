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
        Schema::table('p_o_sells', function (Blueprint $table) {
            //
            $table->string('foc')->nullable()->after('discount');
            $table->string('totalQty')->nullable()->after('discount');
            $table->string('company_price')->nullable()->after('totalQty');
            $table->string('commercial_tax')->nullable()->after('company_price');
            $table->string('amount')->nullable()->after('commercial_tax');
            $table->string('name1')->nullable();
            $table->string('name2')->nullable();
            $table->string('name3')->nullable();
            $table->string('price1')->nullable();
            $table->string('price2')->nullable();
            $table->string('price3')->nullable();
            $table->string('retail1')->nullable();
            $table->string('retail2')->nullable();
            $table->string('retail3')->nullable();
            $table->string('wholesale1')->nullable();
            $table->string('wholesale2')->nullable();
            $table->string('wholesale3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('p_o_sells', function (Blueprint $table) {
            $table->dropColumn('foc');
            $table->dropColumn('totalQty');
            $table->dropColumn('company_price');
            $table->dropColumn('commercial_tax');


            $table->dropColumn('price1');
            $table->dropColumn('price2');
            $table->dropColumn('price3');
            $table->dropColumn('retail1');
            $table->dropColumn('retail2');
            $table->dropColumn('retail3');
            $table->dropColumn('wholesale1');
            $table->dropColumn('wholesale2');
            $table->dropColumn('wholesale3');
        });
    }
};
