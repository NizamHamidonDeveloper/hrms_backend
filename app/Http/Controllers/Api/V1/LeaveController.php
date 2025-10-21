<?php
 
namespace App\Http\Controllers\Api\V1;
 
use App\Http\Controllers\Controller;
use App\Models\CurrentLeave;
use App\Models\LeaveApplication;
use App\Models\LeaveApproval;
use App\Models\LeaveRequests;
use App\Models\LeaveStatus;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\PublicHoliday;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use \Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    public function applyLeave(Request $request)
    {
        try{
            $user = $request->user()->load('profile');

            $validator = Validator::make($request->all(), [
                'type' => 'required|string',
                'date_from' => 'required|date_format:Y-m-d',
                'date_to' => 'required|date_format:Y-m-d',
                'duration' => 'required|int',
                'reason' => 'required',
            ]);

            
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }

            $currentDate = Carbon::now()->format('Y-m-d');
            $currentYear = Carbon::now()->year;

            $date_from = Carbon::parse($request->date_from);
            $date_to = Carbon::parse($request->date_to);

            $leaveTypeMap = Cache::rememberForever('leave_type_name_to_id', function () {
                return DB::table('leave_types')->pluck('id', 'name');
            });

            // Translate user input
            $leaveTypeId = $leaveTypeMap[$request->type] ?? null;

            if (!$leaveTypeId) {
                throw new Exception("Unknown Leave Type. Contact Administrator for further action.",500);
            }

            $formData = [
                'staff_no'  => $user->profile->staff_no,
                'status'    => 1,
                'type'      => $leaveTypeId,
                'date_from' => $date_from->format('Y-m-d'),
                'date_to'   => $date_to->format('Y-m-d'),
                'duration'  => $request->duration,
                'reason'    => $request->reason
            ];
    
            if(!(Profile::where('staff_no', $formData['staff_no'])->exists())){
                throw new Exception("Staff No does not exist!",500);
            }

            // avoid apply leave on past date
            if($date_from->format('Y-m-d') < $currentDate || $date_to->format('Y-m-d') < $currentDate){
                throw new Exception("Unable to apply leave for past dates!",500);
            }

            // avoid duplicate leave application on same date
            if(LeaveApplication::where(['date_from' => $formData['date_from'], 'staff_no' => $formData['staff_no']])->whereNot('status', static::$LEAVE_REJECTED)->exists()){
                throw new Exception("Leave already applied on selected date",500);
            }

            // PH check
            $dates = [];
            foreach (CarbonPeriod::create($formData['date_from'], $formData['date_to']) as $date) {
                $dates[] = $date->format('Y-m-d'); 
            }

            $public_holiday_count = PublicHoliday::whereIn('holiday_date', $dates)->where('year', $currentYear)->count();

            $duration = $date_from->diffInDays($date_to) + 1;
            $actual_duration = $duration - $public_holiday_count;

            //double check for duration
            if($formData['duration'] != ($actual_duration)){
                $formData['duration'] = $actual_duration;
            }

            if($formData['duration'] > 0){

                $balance = getCurrentLeaveBalance($user, $leaveTypeId, $currentYear);

                if($balance > 0){
                    DB::beginTransaction();
                    $leave_application = LeaveApplication::create($formData);
                    echo 'Leave application created successfully.';
    
                    $approvals = LeaveApproval::where('staff_no', $leave_application->staff_no)->get();
    
                    if($approvals->count() > 0){
                        foreach($approvals as $approval){
                            $data = [
                                'leave_application_id' => $leave_application->id,
                                'approval_id'          => $approval->profile_id,
                                'role_id'              => $approval->role_id,
                                'status'               => 1
                            ];
        
                            LeaveRequests::create($data);
                            echo 'Leave requests created successfully';
                        }
                        DB::commit();
                        return response()->json([
                            'success' => true, 
                            'message' => 'Leave applied Successfully. Awaiting approval.'
                        ], 200);
                    }else{
                        throw new Exception("No approval assigned to current user.",500);
                    }
                }else{
                    throw new Exception("Insufficient Leave Balance.",500);
                }


            }else{
                throw new Exception("Duration must be exceed 1 day!",500);
            }
            
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                    'success' => false, 
                    'message' => $e->getMessage()
                ], $e->getCode());
        }
    }

    public function approveLeave(Request $request){
        try{

            $validator = Validator::make($request->all(), [
                'leave_id' => 'required|int',
                'approval_id' => 'required|int'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = $request->user()->load('profile');

            $reqData = [
                'comment' => $request->comment,
                'status'  => 2
            ];

            $approval_id = $request->approvalId;

            $leave_application = LeaveApplication::with('leave_requests.role')->find($request->leave_id);
            $current_request = $leave_application->leave_requests->where('approval_id', $approval_id);

            if($leave_application && $current_request){
                if(!$leave_application->leave_requests->status){
                    $leave_application->leave_requests->update(['status' => 2]);
                }else{
                    throw new Exception('Leave application already approved by user id : '. $approval_id, 500);
                }
            }else{
                throw new Exception('Leave application not found', 500);
            }

            if(!($leave_application->leave_requests->where('status', 1)->count() > 0)){
                $leave_application->update($reqData);

                $currentYear = Carbon::parse($leave_application->date_from)->year;
                $balance = getCurrentLeaveBalance($user, $leave_application->leavetype_id, $currentYear);

                $latestBalance = $balance - $leave_application->duration;
                CurrentLeave::where(['user_id'=>$user->id, 'leavetype_id' => $leave_application->leavetype_id, 'year' => $currentYear])->update(['leave_balance'=>$latestBalance]);

                return response()->json([
                    'success' => true, 
                    'message' => 'All leave requests approved successfully.'
                ], 200);
            }

            return response()->json([
                'success' => true, 
                'message' => 'Leave requests approved successfully. Awaiting other approvals'
            ], 200);

        }catch(Exception $e){

            return response()->json([
                    'success' => false, 
                    'message' => $e->getMessage()
                ], $e->getCode());
        }
    }

    public function rejectLeave(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'leave_id' => 'required|int',
                'approval_id' => 'required|int'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = $request->user()->load('profile');

            $reqData = [
                'comment' => $request->comment,
                'status'  => 3
            ];

            $approval_id = $request->approvalId;

            $leave_application = LeaveApplication::with('leave_requests.role')->find($request->leave_id);
            $leave_application->update($reqData);
            $current_request = $leave_application->leave_requests->where('approval_id', $approval_id);

            if($leave_application && $current_request){
                if(!$leave_application->leave_requests->status){
                    $leave_application->leave_requests->update(['status' => 3]);
                }else{
                    throw new Exception('Leave application already approved by user id : '. $approval_id, 500);
                }
            }else{
                throw new Exception('Leave application not found', 500);
            }


        }catch(Exception $e){

            return response()->json([
                    'success' => false, 
                    'message' => $e->getMessage()
                ], $e->getCode());
        }
    }

}