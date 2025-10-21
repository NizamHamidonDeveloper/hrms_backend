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
        if(!Schema::hasTable('leave_approval')){
            Schema::create('leave_approval', function (Blueprint $table) {
                $table->id(); // bigint unsigned AUTO_INCREMENT PRIMARY KEY
                $table->string('staff_no');
                $table->unsignedBigInteger('profile_id');
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('status');
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
        Schema::dropIfExists('leave_approval');
    }
};
