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

        Schema::table('working_years', function (Blueprint $table) {
            $table->bigInteger('leavetype_id')->nullable()->after('id');
            $table->decimal('tier')->nullable()->after('leavetype_id');
            $table->dropColumn('name');
            $table->dropColumn('working_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('working_years', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->integer('working_period')->nullable();
            $table->dropColumn('leavetype_id');
            $table->dropColumn('tier');
        });
    }
};
