<?php

namespace App\Http\Controllers\Api\V1\SystemSettings;

use App\Http\Controllers\Controller;
use App\Services\SystemSettings\LeaveManagement\LeaveManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class LeaveManagementController extends Controller
{
    protected LeaveManagementService $leaveService;

    public function __construct(LeaveManagementService $leaveService)
    {
        $this->leaveService = $leaveService;
    }

    public function configure(Request $request)
    {
        $this->log_enabled = true;
        try {
            DB::beginTransaction();

            $result = $this->leaveService->update($request->all());

            DB::commit();
            $this->handleSuccessResponse($request, true, 'Configure Leave Type');

        } catch (Exception $e) {
            DB::rollBack();
            $this->handleException($request, $e->getMessage());
        }

        return $this->uniformedResponse();
    }

    public function list(Request $request, int $id){
        try{
            $result = $this->leaveService->getData($id);
            $this->handleSuccessResponse($request, true, 'Configuration value', ['data' => $result]);
        } catch (Exception $e) {
            $this->handleException($request, $e->getMessage());
        }

        return $this->uniformedResponse();
    }
}
