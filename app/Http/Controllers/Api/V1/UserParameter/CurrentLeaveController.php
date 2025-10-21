<?php
 
namespace App\Http\Controllers\Api\V1\UserParameter;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CurrentLeave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Exception;
  
  
class CurrentLeaveController extends Controller
{
    public function listforce(Request $request){
        return CurrentLeave::all();
    }

    public function list(Request $request, $year, $userid = null){
        try{
            if($userid){
                $currentleave = CurrentLeave::where('year', $year)->where('user_id',$userid)->where('status', static::$DATA_STATUS_ACTIVE)->get();
            }
            else{
                $currentleave = CurrentLeave::where('year', $year)->where('status', static::$DATA_STATUS_ACTIVE)->get();
            }

            $this->handleSuccessResponse($request, true, 'List Current Leave',['data' => $currentleave]);
        }
        catch(Exception $e){
            $this->handleException($request, $e->getMessage());
        }
        
        return $this->uniformedResponse();
    }

    public function insert(Request $request){
        $this->log_enabled = true;
        try{
            DB::beginTransaction();

            $this->validate($request);
            $this->checkExisting($request);

            $formData = [
                'user_id' => $request->user_id,
                'year' => $request->year,
                'leavetype_id' => $request->leavetype_id,
                'leave_balance' => $request->leave_balance,
                'status'=> $request->status,
                'created_by' => Auth::id()
            ];

            $data = CurrentLeave::create($formData);

            DB::commit();

            $this->handleSuccessResponse($request, true, 'Insert User Current Leave Record',['data' => $data]);

            $this->log($request, 'Insert New User Current Leave Record', LogType::Success);
        }
        catch(ValidationException $e){
            $this->handleException($request, $e->errors(), 422);
        }
        catch(Exception $e){
            $this->handleException($request, $e->getMessage());
        }

        return $this->uniformedResponse();
    }

    public function update(Request $request, $id){
        $this->log_enabled = true;
        try{
            DB::beginTransaction();

            $this->validate($request);
            $update = CurrentLeave::find($id);
            if(!$update) throw new Exception('Record does not exist');
            $formData = [
                'user_id' => $request->user_id,
                'year' => $request->year,
                'leavetype_id' => $request->annual,
                'leave_balance' => $request->compassionate,
                'status'=> $request->status,
                'updated_by' => Auth::id()
            ];

            $update->update($formData);

            DB::commit();

            $this->handleSuccessResponse($request, true, 'Update User Current Leave Record',['data' => $update]);
        }
        catch(ValidationException $e){
            $this->handleException($request, $e->errors(), 422);
        }
        catch(Exception $e){
            $this->handleException($request, $e->getMessage());
        }

        return $this->uniformedResponse();
    }

    public function delete(Request $request, $id){
        $this->log_enabled = true;
        try{
            DB::beginTransaction();

            $delete = CurrentLeave::find($id);
            if(!$delete){
                throw new Exception('Record does not exist');
            }
            $formData = [
                'status'=> static::$DATA_STATUS_DELETED,
                'updated_by' => Auth::id()
            ];
            $delete->update($formData);

            DB::commit();

            $this->handleSuccessResponse($request, true, 'Delete User Current Leave Record');
        }
        catch(Exception $e){
            $this->handleException($request, $e->getMessage());
        }

        return $this->uniformedResponse();
    }

    private function validate(Request $request){
        return true;
    }

    private function checkExisting(Request $request){
        $record = CurrentLeave::where('user_id', $request->user_id)
            ->where('year', $request->year)
            ->where('leavetype_id', $request->leavetype_id)
            ->where('status', static::$DATA_STATUS_ACTIVE)
            ->first();
        if($record){
            throw new Exception('Record for User id: '.$request->user_id." for year: ".$request->year." and leavetype_id: ".$request->leavetype_id." already exist. Please update instead");
        }
    }
}