<?php
 
namespace App\Http\Controllers\Api\V1\UserParameter;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkingYears;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Exception;
  
  
class WorkingYearsController extends Controller
{
    public function listforce(Request $request)
    {
        return WorkingYears::all();
    }

    public function list(Request $request, $id = null){
        try{
            if($id){
                $data = WorkingYears::where('id', $id)->get();
            }
            else{
                $data = WorkingYears::where('status', static::$DATA_STATUS_ACTIVE)->get();
            }

            $this->handleSuccessResponse($request, true, 'List Working Years',['data' => $data]);
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
                'working_period' => $request->working_period,
                'status'=> $request->status,
                'created_by' => Auth::id()
            ];

            $data = WorkingYears::create($formData);
            DB::commit();

            $this->handleSuccessResponse($request, true, 'Insert New Working Years',['data' => $data]);
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
            $update = WorkingYears::find($id);
            if(!$update) throw new Exception('Record does not exist');
            $formData = [
                'name' => $request->name,
                'working_period' => $request->working_period,
                'status'=> $request->status,
                'updated_by' => Auth::id()
            ];

            $update->update($formData);
            DB::commit();

            $this->handleSuccessResponse($request, true, 'Update Working Years',['data' => $update]);
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
            $delete = WorkingYears::find($id);
            if(!$delete){
                throw new Exception('Record does not exist');
            }
            $formData = [
                'status'=> static::$DATA_STATUS_DELETED,
                'updated_by' => Auth::id()
            ];
            $delete->update($formData);
            DB::commit();
            $this->handleSuccessResponse($request, true, 'Delete Working Years');
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