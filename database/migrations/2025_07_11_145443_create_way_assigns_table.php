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
        Schema::create('way_assigns', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phno')->nullable();
            $table->string('address')->nullable();
            $table->string('location')->nullable();
            $table->string('sale_person')->nullable();
            $table->string('assign')->nullable();
            $table->string('date')->nullable();
            $table->string('call')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('way_assigns');
    }
};
