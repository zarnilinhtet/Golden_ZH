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
        Schema::create('way_call_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('doctor_name')->nullable();
            $table->string('designation')->nullable();
            $table->string('speciality')->nullable();
            $table->string('hp_clinic')->nullable();
            $table->string('address')->nullable();
            $table->string('image')->nullable();
            $table->string('type')->nullable();
           $table->string('location')->nullable();
            $table->string('date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('way_call_tasks');
    }
};
