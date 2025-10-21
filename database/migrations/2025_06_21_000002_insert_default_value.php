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
        // WARNING: this file will delete all existing data for
        //          user parameter table, then insert back the default value.
        //          This is to ensure all working environment will have the same
        //          value inside the user parameter table.

        // leave_type
        DB::table('leave_type')->truncate();
        DB::table('leave_type')->insert([
            ['name' => 'Annual Leave', 'status' => 1, 'created_by' => 1],
            ['name' => 'Sick Leave', 'status' => 1, 'created_by' => 1],
            ['name' => 'Emergency Leave', 'status' => 1, 'created_by' => 1],
            ['name' => 'Unpaid Leave', 'status' => 1, 'created_by' => 1],
            ['name' => 'Maternity Leave', 'status' => 1, 'created_by' => 1],
            ['name' => 'Paternity Leave', 'status' => 1, 'created_by' => 1],
            ['name' => 'Study Leave', 'status' => 1, 'created_by' => 1],
            ['name' => 'Compassionate Leave', 'status' => 1, 'created_by' => 1]
        ]);

        // gender
        DB::table('gender')->truncate();
        DB::table('gender')->insert([
            ['name' => 'Male', 'status' => 1, 'created_by' => 1],
            ['name' => 'Female', 'status' => 1, 'created_by' => 1],
        ]);

        // staff_status
        DB::table('staff_status')->truncate();
        DB::table('staff_status')->insert([
            ['name' => 'Active', 'status' => 1, 'created_by' => 1],
            ['name' => 'Resigned', 'status' => 1, 'created_by' => 1],
            ['name' => 'Terminated', 'status' => 1, 'created_by' => 1]
        ]);

        // employment_type
        DB::table('employment_type')->truncate();
        DB::table('employment_type')->insert([
            ['name' => 'Permanent', 'status' => 1, 'created_by' => 1],
            ['name' => 'Contractual', 'status' => 1, 'created_by' => 1]
        ]);

        // working_state
        DB::table('working_state')->truncate();
        DB::table('working_state')->insert([
            ['name' => 'Perak', 'status' => 1, 'created_by' => 1],
            ['name' => 'Selangor', 'status' => 1, 'created_by' => 1],
            ['name' => 'Pahang', 'status' => 1, 'created_by' => 1],
            ['name' => 'Kelantan', 'status' => 1, 'created_by' => 1],
            ['name' => 'Johor', 'status' => 1, 'created_by' => 1],
            ['name' => 'Kedah', 'status' => 1, 'created_by' => 1],
            ['name' => 'Labuan', 'status' => 1, 'created_by' => 1],
            ['name' => 'Melaka', 'status' => 1, 'created_by' => 1],
            ['name' => 'Negeri Sembilan', 'status' => 1, 'created_by' => 1],
            ['name' => 'Pulau Pinang', 'status' => 1, 'created_by' => 1],
            ['name' => 'Sarawak', 'status' => 1, 'created_by' => 1],
            ['name' => 'Perlis', 'status' => 1, 'created_by' => 1],
            ['name' => 'Sabah', 'status' => 1, 'created_by' => 1],
            ['name' => 'Terengganu', 'status' => 1, 'created_by' => 1],
            ['name' => 'Kuala Lumpur', 'status' => 1, 'created_by' => 1]
        ]);

        // working_hour_type
        DB::table('working_hour_type')->truncate();
        DB::table('working_hour_type')->insert([
            ['name' => 'Normal', 'status' => 1, 'created_by' => 1],
            ['name' => 'Shift', 'status' => 1, 'created_by' => 1]
        ]);

        // leave_status
        DB::table('leave_status')->truncate();
        DB::table('leave_status')->insert([
            ['name' => 'In Progress', 'status' => 1, 'created_by' => 1],
            ['name' => 'Approved', 'status' => 1, 'created_by' => 1],
            ['name' => 'Rejected', 'status' => 1, 'created_by' => 1]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
