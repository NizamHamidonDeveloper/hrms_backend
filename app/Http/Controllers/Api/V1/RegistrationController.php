<?php
 
namespace App\Http\Controllers\Api\V1;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use App\Models\CurrentLeave;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Enum\LogType;
use App\Enum\RegistrationCheckType;
  
class RegistrationController extends Controller
{
    public function register(Request $request){
        $this->log_enabled = true;
        try{
            DB::beginTransaction();

            $this->validateRegistrationData($request);
            $this->validateUsernameStaffnoEmail($request);

            $formData = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password,
                'status' => static::$DATA_STATUS_ACTIVE
            ];
    
            $formData['password'] = bcrypt($request->password);
    
            $user = User::create($formData);
            if(!$user){
                throw new Exception('User create failed');
            }
            
            $profileData = [
                'user_id' => $user->id,
                'staffstatus_id' => $request->staffstatus_id,
                'staff_no' => $request->staff_no,
                'rank' => $request->rank,
                'gender_id' => $request->gender_id,
                'position' => $request->position,
                'phone_no' => $request->phone_no,
                'fax_no' => $request->fax_no,
                'company_id' => $request->company_id,
                'department_id' => $request->department_id,
                'date_joined' => $request->date_joined,
                'last_date_working' => $request->last_date_working,
                'workingyears_id' => $request->workingyears_id,
                'employmenttype_id' => $request->employmenttype_id,
                'workingstate_id' => $request->workingstate_id,
                'workinghours_id' => $request->workinghours_id,
                'domain_id' => $request->domain_id,
                'role_id' => $request->role_id,
                'status' => static::$DATA_STATUS_ACTIVE,
                'created_by' => Auth::id()
            ];

            $profile = Profile::create($profileData);
            if(!$profile){
                throw new Exception('User Profile create failed');
            }

            $leaveType = LeaveType::all();

            foreach($leaveType as $data){
                $currentLeaveData = [
                        'user_id' => $user->id,
                        'year' => Carbon::now()->year,
                        'leavetype_id' => $data->id,
                        'leave_balance' => 0,
                        'status' => static::$DATA_STATUS_ACTIVE,
                        'created_by' => Auth::id()
                ];

                $currentLeave = CurrentLeave::create($currentLeaveData);
                if(!$currentLeave){
                    throw new Exception('Current Leave generate failed');
                }
            }

            DB::commit();
            $this->handleSuccessResponse($request, true, 'Registered a user',['user' => $user, 'profile' => $profile]);
        }
        catch(ValidationException $e){
            $this->handleException($request, $e->errors(), 422);
        }
        catch(Exception $e){
            $this->handleException($request, $e->getMessage());
        }
        
        return $this->uniformedResponse();
    }
    
    private function validateRegistrationData(Request $request){
        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required|min:5',
            'password' => 'required|min:8|confirmed',
            'staffstatus_id' => 'required',
            'gender_id' => 'required',
            'position' => 'required',
            'phone_no' => 'required',
            'company_id' => 'required',
            'department_id' => 'required',
            'date_joined' => 'required',
            'workingyears_id' => 'required',
            'employmenttype_id' => 'required',
            'workingstate_id' => 'required',
            'workinghours_id' => 'required',
            'domain_id' => 'required',
            'role_id' => 'required',
        ]);
    }

    public function issueToken(Request $request){
        $request->request->add([
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'), // Replace with real client ID
            'client_secret' => env('PASSPORT_PASSWORD_CLIENT_SECRET'), // Replace with real client secret
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '',
        ]);

        // ✅ Issue token internally
        $tokenResponse = App::make(AccessTokenController::class)
            ->issueToken(App::make(ServerRequestInterface::class), App::make(ResponseInterface::class));

        $tokenData = json_decode($tokenResponse->getContent(), true);
        
        return $tokenData;
    }

    public function check(Request $request, $type){
        try{
            $type = RegistrationCheckType::tryFrom($type);

            if(!$type) throw new Exception('Invalid check type');

            switch($type){
                case RegistrationCheckType::Username:
                    $this->checkUsername($request);
                    break;
                case RegistrationCheckType::StaffNo:
                    $this->checkStaffNo($request);
                    break;
                case RegistrationCheckType::Email:
                    $this->checkEmail($request);
                    break;
            }
        }
        catch(ValidationException $e){
            $this->handleException($request, $e->errors(), 422);
        }
        catch(Exception $e){
            $this->handleException($request, $e->getMessage());
        }

        return $this->uniformedResponse();
    }

    private function checkUsername(Request $request){
        $validated = $request->validate(['username' => 'required|min:5']);
        $user = User::whereRaw('LOWER(username) = ?', [strtolower($request->username)])->where('status',static::$DATA_STATUS_ACTIVE)->first();
        if($user){
            $this->handleSuccessResponse($request, false, 'Username already exist');
        }
        else{
            $this->handleSuccessResponse($request, true, 'Username available');
        }
    }

    private function checkStaffNo(Request $request){
        $validated = $request->validate(['staff_no' => 'required']);
        $profile = Profile::whereRaw('LOWER(staff_no) = ?', [strtolower($request->staff_no)])->where('status',static::$DATA_STATUS_ACTIVE)->first();
        if($profile){
            $this->handleSuccessResponse($request, false, 'Staff no already exist');
        }
        else{
            $this->handleSuccessResponse($request, true, 'Staff no available');
        }
    }

    private function checkEmail(Request $request){
        $validated = $request->validate(['email' => 'required|email']);
        $user = User::whereRaw('LOWER(email) = ?', [strtolower($request->email)])->where('status',static::$DATA_STATUS_ACTIVE)->first();
        if($user){
            $this->handleSuccessResponse($request, false, 'Email already exist');
        }
        else{
            $this->handleSuccessResponse($request, true, 'Email available');
        }
    }

    private function validateUsernameStaffnoEmail(Request $request){
        $this->checkUsername($request);
        $check = $this->message_response['status'];
        if(!$check) throw new Exception($this->message_response['message']);

        $this->checkEmail($request);
        $check = $this->message_response['status'];
        if(!$check) throw new Exception($this->message_response['message']);

        $this->checkStaffNo($request);
        $check = $this->message_response['status'];
        if(!$check) throw new Exception($this->message_response['message']);
    }
}