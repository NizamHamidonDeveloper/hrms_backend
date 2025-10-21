<?php

use App\Models\CurrentLeave;
use App\Models\LeaveApplication;

if (!function_exists('getCurrentLeaveBalance')) {
    function getCurrentLeaveBalance($user, $leaveTypeId, $currentYear){
        $totalOnHoldBalance = 0;
        $current_leave = CurrentLeave::where(['user_id' => $user->id, 'leavetype_id' => $leaveTypeId, 'year' => $currentYear])->first();
        $pendingLeave = LeaveApplication::where(['user_id' => $user->profile->staff_no, 'leavetype_id' => $leaveTypeId, 'status' => 1])->get();

        foreach($pendingLeave as $leave){
            $totalOnHoldBalance += $leave->duration; 
        }

        return $current_leave - $totalOnHoldBalance;
    }
}
