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
        Schema::create('advanced_setting', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leavetype_id');
            $table->unsignedInteger('prorata_calculation_check')->default(0);
            $table->unsignedInteger('negative_balance_check')->default(0);
            $table->unsignedInteger('audit_logging_check')->default(0);
            $table->unsignedInteger('leave_encashment_check')->nullable(0);
            $table->unsignedInteger('encashment_maximum_day')->nullable(0);
            $table->text('custom_leave_code')->nullable();
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
        Schema::dropIfExists('advanced_setting');
    }
};
