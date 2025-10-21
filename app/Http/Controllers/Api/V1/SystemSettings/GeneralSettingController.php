<?php

namespace App\Http\Controllers\Api\V1\SystemSettings;

use App\Http\Controllers\Controller;
use App\Services\SystemSettings\GeneralSettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class GeneralSettingController extends Controller
{
    protected GeneralSettingService $generalSettingService;

    public function __construct(GeneralSettingService $generalSettingService)
    {
        $this->generalSettingService = $generalSettingService;
    }

    public function configure(Request $request)
    {
        $this->log_enabled = true;
        try {
            DB::beginTransaction();

            $result = $this->generalSettingService->addOrUpdate($request->all());

            DB::commit();
            $this->handleSuccessResponse($request, true, $result['message']);

        } catch (Exception $e) {
            DB::rollBack();
            $this->handleException($request, $e->getMessage());
        }

        return $this->uniformedResponse();
    }

    public function delete(Request $request)
    {
        $this->log_enabled = true;
        try {
            DB::beginTransaction();

            $result = $this->generalSettingService->delete($request->all());

            DB::commit();
            $this->handleSuccessResponse($request, true, 'delete General Settings');

        } catch (Exception $e) {
            DB::rollBack();
            $this->handleException($request, $e->getMessage());
        }

        return $this->uniformedResponse();
    }

    public function list(Request $request, int $company_id){
        try{

            $result = $this->generalSettingService->list($company_id);
            
            $this->handleSuccessResponse($request, true, 'List General Setting', ["data" => $result]);
        }
        catch(Exception $e){
            $this->handleException($request, $e->getMessage());
        }

        return $this->uniformedResponse();
    }
}
