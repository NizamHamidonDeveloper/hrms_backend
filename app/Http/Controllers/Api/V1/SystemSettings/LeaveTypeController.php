<?php
 
namespace App\Http\Controllers\Api\V1\SystemSettings;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Services\SystemSettings\LeaveManagement\LeaveTypeService;
use App\Services\SystemSettings\LeaveManagement\LeaveManagementService;
  
class LeaveTypeController extends Controller
{
    protected LeaveTypeService $leaveTypeService;

    public function __construct(LeaveTypeService $leaveTypeService)
    {
        $this->leaveTypeService = $leaveTypeService;
    }

    public function insert(Request $request){
        $this->log_enabled = true;

        try{
            DB::beginTransaction();

            $result = $this->leaveTypeService->insert($request->all());
            
            DB::commit();

            $this->handleSuccessResponse($request, true, 'Add Leave Type',['data' => $data]);
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

            $this->leaveTypeService->delete($id);

            DB::commit();
            
            $this->handleSuccessResponse($request, true, 'Delete Leave Type');
        }
        catch(ValidationException $e){
            $this->handleException($request, $e->errors(), 422);
        }
        catch(Exception $e){
            $this->handleException($request, $e->getMessage());
        }

        return $this->uniformedResponse();
    }

    public function listAll(Request $request){
        try{
            DB::beginTransaction();

            $result = $this->leaveTypeService->list(0);

            DB::commit();
            
            $this->handleSuccessResponse($request, true, 'List Leave Type', ["data" => $result]);
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