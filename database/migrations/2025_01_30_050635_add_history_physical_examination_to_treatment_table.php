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
        Schema::table('treatments', function (Blueprint $table) {
            //
            $table->longText('history')->after('investigation')->nullable();
            $table->longText('physical_examination')->after('history')->nullable();
            $table->string('file1')->after('physical_examination')->nullable();
            $table->string('file2')->after('file1')->nullable();
            $table->string('file3')->after('file2')->nullable();
            $table->string('file4')->after('file3')->nullable();
            $table->string('file5')->after('file4')->nullable();
            $table->string('doctor_id')->after('file5')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('treatments', function (Blueprint $table) {
            //
            $table->dropColumn('history');
            $table->dropColumn('physical_examination');
            $table->dropColumn('file1');
            $table->dropColumn('file2');
            $table->dropColumn('file3');
            $table->dropColumn('file4');
            $table->dropColumn('file5');
            $table->dropColumn('doctor_id');
        });
    }
};
