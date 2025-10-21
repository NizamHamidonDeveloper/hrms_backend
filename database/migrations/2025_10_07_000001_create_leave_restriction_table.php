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
        Schema::create('leave_restriction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leavetype_id');
            $table->unsignedInteger('minimum_duration')->default(0);
            $table->unsignedInteger('maximum_duration')->default(0);
            $table->unsignedInteger('max_consecutive_days')->default(0);
            $table->json('blackout_dates')->nullable();
            $table->json('required_documents')->nullable();
            $table->json('department_restriction_ids')->nullable();
            $table->json('roles_restriction_ids')->nullable();
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
        Schema::dropIfExists('leave_restriction');
    }
};
