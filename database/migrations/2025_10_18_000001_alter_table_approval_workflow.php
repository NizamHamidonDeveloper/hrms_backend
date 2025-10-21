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
        if(Schema::hasTable('approval_workflow')){
            Schema::table('approval_workflow', function (Blueprint $table) {
                $table->string('name')->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasTable('approval_workflow')){
            Schema::table('approval_workflow', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
    }
};
