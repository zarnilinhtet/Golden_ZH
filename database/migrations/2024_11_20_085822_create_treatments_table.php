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
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->nullable();
            $table->string('name', 191)->nullable();
            $table->string('phno', 191)->nullable();
            $table->string('customer_age')->nullable();
            $table->string('customer_gender')->nullable();
            $table->string('nrc', 191)->nullable();
            $table->string('cdc_no', 191)->nullable();
            $table->string('dob', 191)->nullable();

            $table->string('patient_status')->nullable();
            $table->string('patient_type')->nullable();
            $table->string('inout_patient', 191)->nullable();
            $table->string('department', 191)->nullable();
            $table->string('customer_deposit', 191)->nullable();
            $table->string('company', 191)->nullable();
            $table->string('address', 191)->nullable();


            $table->string('branch', 191)->nullable();
            $table->longText('investigation')->nullable();

            $table->longText('diagnosis')->nullable();
            $table->longText('treatment')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
