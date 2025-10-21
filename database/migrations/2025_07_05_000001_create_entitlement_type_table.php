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
        Schema::create('entitlement_type', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('status');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('entitlement_type')->insert([
            ['name' => 'Annual', 'status' => 1, 'created_by' => 1],
            ['name' => 'Monthly', 'status' => 1, 'created_by' => 1],
            ['name' => 'Unlimited', 'status' => 1, 'created_by' => 1],
            ['name' => 'Custom', 'status' => 1, 'created_by' => 1]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entitlement_type');
    }
};
