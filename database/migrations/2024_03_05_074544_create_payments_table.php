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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('account_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('amount')->nullable();
            $table->string('date')->nullable();
            $table->string('note')->nullable();
            $table->string('edit_delete')->nullable();
            $table->string('voucher_no')->nullable();
            $table->string('invoice_no')->nullable();
            $table->foreignId('transfer_account_id')->nullable();
            $table->string('receiver_name')->nullable();
            $table->string('reference_no')->nullable();
            $table->string('opening_balance')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
