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

        Schema::table('current_leave', function (Blueprint $table) {
            $table->bigInteger('leavetype_id')->nullable()->after('year');
            $table->decimal('leave_balance')->default(0)->after('leavetype_id');
            $table->dropColumn('annual');
            $table->dropColumn('compassionate');
            $table->dropColumn('maternity');
            $table->dropColumn('hospitalisation');
            $table->dropColumn('medical');
            $table->dropColumn('paternity');
            $table->dropColumn('study');
            $table->dropColumn('marriage');
            $table->dropColumn('pilgrimage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('current_leave', function (Blueprint $table) {
            $table->decimal('annual')->default(0);
            $table->decimal('compassionate')->default(0);
            $table->decimal('maternity')->default(0);
            $table->decimal('hospitalisation')->default(0);
            $table->decimal('medical')->default(0);
            $table->decimal('paternity')->default(0);
            $table->decimal('study')->default(0);
            $table->decimal('pilgrimage')->default(0);
            $table->decimal('marriage')->default(0);
            $table->dropColumn('leavetype_id');
            $table->dropColumn('leave_balance');
        });
    }
};
