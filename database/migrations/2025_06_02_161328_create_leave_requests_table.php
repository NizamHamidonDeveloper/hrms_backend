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
        if(!Schema::hasTable('leave_requests')){
            Schema::create('leave_requests', function (Blueprint $table) {
                $table->id(); 
                $table->string('staff_no');
                $table->unsignedBigInteger('leave_application_id');
                $table->string('approval_id')->comment('profile id');
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
