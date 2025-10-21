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
        Schema::create('gender', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            $table->integer('status');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('staff_status', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            $table->integer('status');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('company', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            $table->integer('status');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('department', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            $table->integer('status');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('employment_type', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            $table->integer('status');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('working_state', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            $table->integer('status');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('working_hour_type', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            $table->integer('status');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('current_leave', function (Blueprint $table) {
            $table->id()->primary();
            $table->bigInteger('user_id')->nullable();
            $table->integer('year');
            $table->integer('annual');
            $table->integer('compassionate');
            $table->integer('maternity');
            $table->integer('hospitalisation');
            $table->integer('medical');
            $table->integer('paternity');
            $table->integer('study');
            $table->integer('pilgrimage');
            $table->integer('marriage');
            $table->integer('status');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gender');
        Schema::dropIfExists('staff_status');
        Schema::dropIfExists('company');
        Schema::dropIfExists('department');
        Schema::dropIfExists('employment_type');
        Schema::dropIfExists('working_state');
        Schema::dropIfExists('working_hour_type');
        Schema::dropIfExists('current_leave');
    }
};
