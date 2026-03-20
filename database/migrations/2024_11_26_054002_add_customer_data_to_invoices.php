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
            //
            $table->string('age')->after('address')->nullable();
            $table->string('nrc')->after('age')->nullable();
            $table->string('dob')->after('nrc')->nullable();
            $table->string('cdc_no')->after('dob')->nullable();
            $table->string('company')->after('cdc_no')->nullable();
            $table->string('patient_type')->after('company')->nullable();
            $table->string('service_type')->after('patient_type')->nullable();
            $table->longText('treatment_remark')->after('service_type')->nullable();
            $table->string('treatment_id')->after('customer_id')->nullable();
            $table->string('treatment_and_other')->after('treatment_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
            $table->dropColumn('age');
            $table->dropColumn('nrc');
            $table->dropColumn('dob');
            $table->dropColumn('cdc_no');
            $table->dropColumn('company');
            $table->dropColumn('patient_type');
            $table->dropColumn('service_type');
            $table->dropColumn('treatment_remark');
            $table->dropColum('treatment_id');
            $table->dropColum('treatment_and_other');
        });
    }
};
