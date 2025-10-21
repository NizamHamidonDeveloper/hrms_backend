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
        Schema::create('general_setting', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->datetime('leave_year_start')->nullable();
            $table->unsignedInteger('monday_check')->default(0);
            $table->unsignedInteger('tuesday_check')->default(0);
            $table->unsignedInteger('wednesday_check')->default(0);
            $table->unsignedInteger('thursday_check')->default(0);
            $table->unsignedInteger('friday_check')->default(0);
            $table->unsignedInteger('saturday_check')->default(0);
            $table->unsignedInteger('sunday_check')->default(0);
            $table->integer('status');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_setting');
    }
};
