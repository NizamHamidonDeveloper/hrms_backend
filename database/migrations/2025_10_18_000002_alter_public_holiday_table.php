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
        if(Schema::hasTable('public_holiday')){
            Schema::table('public_holiday', function (Blueprint $table) {
                $table->dropColumn('holiday_name');
                $table->string('holiday_name')->after('holiday_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasTable('public_holiday')){
            Schema::table('public_holiday', function (Blueprint $table) {
                $table->dropColumn('holiday_name');
                $table->bigInteger('holiday_name')->after('holiday_date');
            });
        }
    }
};
