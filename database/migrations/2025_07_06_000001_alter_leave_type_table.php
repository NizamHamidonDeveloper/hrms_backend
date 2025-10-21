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
        if(Schema::hasTable('leave_type')){
            Schema::table('leave_type', function (Blueprint $table) {
                $table->text('color')->nullable();
                $table->string('custom_code')->nullable();
                $table->unsignedBigInteger('entitlementtype_id')->nullable();
                $table->unsignedInteger('carryforward_check')->default(0);
                $table->decimal('carryforward_value')->default(0);
                $table->integer('carryforward_expiry')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasTable('leave_type')){
            Schema::table('leave_type', function (Blueprint $table) {
                $table->dropColumn('color');
                $table->dropColumn('custom_code');
                $table->dropColumn('entitlementtype_id');
                $table->dropColumn('carryforward_check');
                $table->dropColumn('carryforward_value');
                $table->dropColumn('carryforward_expiry');
            });
        }
    }
};
