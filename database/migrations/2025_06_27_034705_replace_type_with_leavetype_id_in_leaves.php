<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if(!Schema::hasColumn('leave_application', 'type')){
            Schema::table('leave_application', function (Blueprint $table) {
                $table->dropColumn('type');
                $table->unsignedBigInteger('leavetype_id')->after('staff_no');
            });
        }
    }

    public function down(): void
    {
        Schema::table('leave_application', function (Blueprint $table) {
            $table->dropColumn('leavetype_id');
            $table->string('type')->after('staff_no');
        });
    }
};
