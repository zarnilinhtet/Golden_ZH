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
        Schema::table('customers', function (Blueprint $table) {
            //
            $table->string('age')->after('address')->nullable();

            $table->string('patient_type')->after('age')->nullable();
            $table->string('file1')->after('inout_patient')->nullable();
            $table->string('file2')->after('file1')->nullable();
            $table->string('file3')->after('file2')->nullable();
            $table->string('file4')->after('file3')->nullable();
            $table->string('file5')->after('file4')->nullable();
            $table->string('nrc')->after('file5')->nullable();
            $table->string('cdc_no')->after('nrc')->nullable();
            $table->string('dob')->after('cdc_no')->nullable();
            $table->string('company')->after('nrc')->nullable();
            $table->string('branch')->after('company')->nullable();
            $table->string('patient_status')->after('branch')->nullable();
            $table->string('gender')->after('patient_status')->nullable();
            $table->string('department')->after('gender')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
            $table->dropColumn('age');
            $table->dropColumn('patient_type');
            $table->dropColumn('file1');
            $table->dropColumn('file2');
            $table->dropColumn('file3');
            $table->dropColumn('file4');
            $table->dropColumn('file5');
            $table->dropColumn('nrc');
            $table->dropColumn('cdc_no');
            $table->dropColumn('dob');
            $table->dropColumn('company');
            $table->dropColumn('branch');
            $table->dropColumn('patient_status');
            $table->dropColumn('gender');
        });
    }
};
