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
        Schema::create('working_years', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            $table->integer('working_period');
            $table->integer('status');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('entitled_leave', function (Blueprint $table) {
            $table->id()->primary();
            $table->bigInteger('workingyears_id')->nullable();
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
        Schema::dropIfExists('workingyears');
        Schema::dropIfExists('entitledleave');
    }
};
