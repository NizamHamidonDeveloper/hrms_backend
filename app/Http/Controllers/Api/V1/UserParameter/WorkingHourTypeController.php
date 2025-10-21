<?php
 
namespace App\Http\Controllers\Api\V1\UserParameter;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkingHourType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Exception;
  
  
class WorkingHourTypeController extends Controller
{
    public function listforce(Request $request){
        return WorkingHourType::all();
    }

    public function list(Request $request, $id = null){
        try{
            if($id){
                $workinghour = WorkingHourType::where('id', $id)->get();
            }
            else{
                $workinghour = WorkingHourType::where('status', static::$DATA_STATUS_ACTIVE)->get();
            }

            $this->handleSuccessResponse($request, true, 'List Working Hour Type',['data' => $workinghour]);
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
            $formData = [
                'name' => $request->name,
                'status'=> $request->status,
                'created_by' => Auth::id()
            ];

            $data = WorkingHourType::create($formData);

            DB::commit();

            $this->handleSuccessResponse($request, true, 'Insert New Working Hour Type',['data' => $data]);
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
            $update = WorkingHourType::find($id);
            if(!$update) throw new Exception('Record does not exist');
            $formData = [
                'name' => $request->name,
                'status'=> $request->status,
                'updated_by' => Auth::id()
            ];

            $update->update($formData);

            DB::commit();

            $this->handleSuccessResponse($request, true, 'Update Working Hour Type',['data' => $update]);
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
            $delete = WorkingHourType::find($id);
            if(!$delete){
                throw new Exception('Record does not exist');
            }
            $formData = [
                'status'=> static::$DATA_STATUS_DELETED,
                'updated_by' => Auth::id()
            ];
            $delete->update($formData);
            DB::commit();
            $this->handleSuccessResponse($request, true, 'Delete Working Hour Type');
        }
        catch(ValidationException $e){
            $this->handleException($request, $e->errors(), 422);
        }
        catch(Exception $e){
            $this->handleException($request, $e->getMessage());
        }

        return $this->uniformedResponse();
    }

    private function validate(Request $request){
        return true;
    }
}