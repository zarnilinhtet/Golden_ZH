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
        Schema::create('return_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id');
            $table->foreignId('customer_id')->nullable();
            $table->foreignId('doctor_id')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('invoice_category')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('phno')->nullable();
            $table->string('address')->nullable();
            $table->string('age')->nullable();
            $table->string('nrc')->nullable();
            $table->string('dob')->nullable();
            $table->string('cdc_no')->nullable();
            $table->string('company')->nullable();
            $table->string('patient_type')->nullable();
            $table->string('service_type')->nullable();
            $table->string('customer_deposit')->nullable();
            $table->string('foc_patient')->nullable();
            $table->string('category')->nullable();
            $table->string('total')->nullable();
            $table->string('status')->nullable();
            $table->string('discount_total')->nullable();
            $table->string('sale_price_category')->nullable();
            $table->string('doctor_commission')->nullable();
            $table->string('doctor_commission_percentage')->nullable();

            $table->longText('treatment_remark')->nullable();
            $table->foreignId('sale_by')->nullable();
            $table->string('treatment_id')->nullable();
            $table->string('treatment_and_other')->nullable();
            $table->string('invoice_date')->nullable();
            $table->string('overdue_date')->nullable();
            $table->string('type')->nullable();
            $table->string('remark')->nullable();
            $table->string('net_total')->nullable();
            $table->string('deposit')->nullable();
            $table->string('remain_balance')->nullable();
            $table->string('location')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_invoices');
    }
};
