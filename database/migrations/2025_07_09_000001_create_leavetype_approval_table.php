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
        Schema::create('leavetype_approval', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leavetype_id');
            $table->unsignedInteger('autoapproval_check')->default(0);
            $table->unsignedBigInteger('escalation_role_id');
            $table->unsignedInteger('escalation_days')->nullable();
            $table->integer('status');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('role_approval', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leavetypeapproval_id');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('approvalworkflow_id');
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
        Schema::dropIfExists('leavetype_approval');
        Schema::dropIfExists('role_approval');
    }
};
