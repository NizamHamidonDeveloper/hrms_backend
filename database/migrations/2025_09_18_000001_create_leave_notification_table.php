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
        Schema::create('leave_notification', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leavetype_id');
            $table->unsignedInteger('onapply_check')->default(0);
            $table->unsignedInteger('onapproval_check')->default(0);
            $table->unsignedInteger('onrejection_check')->default(0);
            $table->unsignedInteger('oncancelation_check')->default(0);
            $table->unsignedInteger('channelemail_check')->default(0);
            $table->unsignedInteger('channelapp_check')->default(0);
            $table->text('additional_recepients')->nullable();
            $table->longText('notification_template')->nullable();
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
        Schema::dropIfExists('notification_template');
    }
};
