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
        if(!Schema::hasTable('leave_application')){
            Schema::create('leave_application', function (Blueprint $table) {
                $table->id(); 
                $table->string('staff_no');
                $table->string('type');
                $table->decimal('duration', 10, 2);
                $table->timestamp('date_from')->default('2000-01-01 00:00:00');
                $table->timestamp('date_to')->default('2000-01-01 00:00:00');
                $table->string('reason');
                $table->unsignedBigInteger('status');
                $table->text('comment')->nullable()->comment('reject reason');
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->unsignedBigInteger('rejected_by')->nullable();
                $table->timestamp('rejected_at')->nullable();
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
        Schema::dropIfExists('leave_application');
    }
};
