<?php

namespace App\Http\Controllers\Api\V1\SystemSettings;

use App\Http\Controllers\Controller;
use App\Services\SystemSettings\ApprovalWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class ApprovalWorkflowController extends Controller
{
    protected ApprovalWorkflowService $approvalWorkflowService;

    public function __construct(ApprovalWorkflowService $approvalWorkflowService)
    {
        $this->approvalWorkflowService = $approvalWorkflowService;
    }

    public function configure(Request $request, int $id = 0)
    {
        $this->log_enabled = true;
        try {
            DB::beginTransaction();

            $data = array_merge($request->all(), $id != 0 ? ['id' => $id]: []);
            $result = $this->approvalWorkflowService->addOrUpdate($data);

            DB::commit();
            $this->handleSuccessResponse($request, true, $result['message']);

        } catch (Exception $e) {
            DB::rollBack();
            $this->handleException($request, $e->getMessage());
        }

        return $this->uniformedResponse();
    }

    public function delete(Request $request, int $id)
    {
        $this->log_enabled = true;
        try {
            DB::beginTransaction();
            
            $data = array_merge($request->all(), ['id' => $id]);
            $result = $this->approvalWorkflowService->delete($data);

            DB::commit();
            $this->handleSuccessResponse($request, true, 'delete Approval Workflow');

        } catch (Exception $e) {
            DB::rollBack();
            $this->handleException($request, $e->getMessage());
        }

        return $this->uniformedResponse();
    }

    public function list(Request $request)
    {
        try{

            $result = $this->approvalWorkflowService->list();
            
            $this->handleSuccessResponse($request, true, 'List Approval Workflow', ["data" => $result]);
        }
        catch(Exception $e){
            $this->handleException($request, $e->getMessage());
        }

        return $this->uniformedResponse();
    }
}
