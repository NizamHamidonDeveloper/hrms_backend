<?php
 
namespace App\Http\Controllers\Api\V1\UserParameter;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EntitledLeave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Exception;
  
class EntitledLeaveController extends Controller
{
    public function listforce(Request $request){
        return EntitledLeave::all();
    }

    public function list(Request $request, $id = null){
        try{
            if($id){
                $entitledleave = EntitledLeave::where('id', $id)->get();
            }
            else{
                $entitledleave = EntitledLeave::where('status', static::$DATA_STATUS_ACTIVE)->get();
            }

            $this->handleSuccessResponse($request, true, 'List Entitled Leave',['data' => $entitledleave]);
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
                'workingyears_id' => $request->workingyears_id,
                'annual' => $request->annual,
                'compassionate' => $request->compassionate,
                'maternity' => $request->maternity,
                'hospitalisation' => $request->hospitalisation,
                'medical' => $request->medical,
                'paternity' => $request->paternity,
                'study' => $request->study,
                'pilgrimage' => $request->pilgrimage,
                'marriage' => $request->marriage,
                'status'=> $request->status,
                'created_by' => Auth::id()
            ];

            $data = EntitledLeave::create($formData);
            DB::commit();
            $this->handleSuccessResponse($request, true, 'Insert New Entitled Leave',['data' => $data]);
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
            $update = EntitledLeave::find($id);
            if(!$update) throw new Exception('Record does not exist');
            $formData = [
                'workingyears_id' => $request->workingyears_id,
                'annual' => $request->annual,
                'compassionate' => $request->compassionate,
                'maternity' => $request->maternity,
                'hospitalisation' => $request->hospitalisation,
                'medical' => $request->medical,
                'paternity' => $request->paternity,
                'study' => $request->study,
                'pilgrimage' => $request->pilgrimage,
                'marriage' => $request->marriage,
                'status'=> $request->status,
                'updated_by' => Auth::id()
            ];

            $update->update($formData);
            DB::commit();
            $this->handleSuccessResponse($request, true, 'Update Entitled Leave',['data' => $update]);
        }
        catch(ValidationException $e){
            $this->handleException($request, $e->errors(), 422);
        }
        catch(Exception $e){
            $this->handleException($request, $e->getMessage());
        }

        return $this->uniformedResponse();
    }

    public function delete($id){
        $this->log_enabled = true;
        try{
            DB::beginTransaction();
            $delete = EntitledLeave::find($id);
            if(!$delete){
                throw new Exception('Record does not exist');
            }
            $formData = [
                'status'=> static::$DATA_STATUS_DELETED,
                'updated_by' => Auth::id()
            ];
            $delete->update($formData);
            DB::commit();
            $this->handleSuccessResponse($request, true, 'Delete Entitled Leave');
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
        $entitledleave = EntitledLeave::where('workingyears_id', $request->workingyears_id)->where('status', static::$DATA_STATUS_ACTIVE)->first();
        if($entitledleave) throw new Exception('Entitled Leave for workingyears_id: '.$request->workingyears_id." already existed. Please update instead.");
    }
}