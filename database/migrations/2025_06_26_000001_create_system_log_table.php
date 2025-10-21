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
        Schema::create('system_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('action');
            $table->text('action_detail')->nullable();
            $table->string('url');
            $table->text('post_data')->nullable();
            $table->text('get_data')->nullable();
            $table->string('controller_name');
            $table->string('method_name');
            $table->integer('log_type');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('url');
            $table->index('created_at');
            $table->index('log_type');
            $table->index('controller_name');
            $table->index('method_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_log');
    }
};
