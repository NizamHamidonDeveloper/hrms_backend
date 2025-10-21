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
        Schema::create('profile', function (Blueprint $table) {
            $table->id()->primary();
            $table->bigInteger('user_id');
            $table->bigInteger('staffstatus_id');
            $table->string('staff_no');
            $table->string('rank')->nullable();
            $table->bigInteger('gender_id');
            $table->string('position');
            $table->string('phone_no');
            $table->string('fax_no')->nullable();
            $table->bigInteger('company_id');
            $table->bigInteger('department_id');
            $table->datetime('date_joined');
            $table->datetime('last_date_working')->nullable();
            $table->bigInteger('workingyears_id');
            $table->bigInteger('employmenttype_id');
            $table->bigInteger('workingstate_id');
            $table->bigInteger('workinghours_id');
            $table->bigInteger('domain_id');
            $table->bigInteger('role_id');
            $table->integer('status');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('role', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            $table->integer('status');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('domain', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
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
        Schema::dropIfExists('profile');
        Schema::dropIfExists('role');
        Schema::dropIfExists('domain');
    }
};
