<?php

namespace App\Services\SystemSettings\LeaveManagement;

use App\Services\BaseService;
use App\Services\SystemSettings\LeaveManagement\LeaveTypeService;
use App\Services\SystemSettings\LeaveManagement\LeaveAccrualService;
use App\Services\SystemSettings\LeaveManagement\LeaveApprovalService;
use App\Services\SystemSettings\LeaveManagement\LeaveNotificationService;
use App\Services\SystemSettings\LeaveManagement\LeaveRestrictionService;
use App\Services\SystemSettings\LeaveManagement\AdvancedLeaveSettingService;
use Illuminate\Support\Facades\DB;

class LeaveManagementService extends BaseService
{
    public function __construct(
        protected LeaveTypeService $typeService,
        protected LeaveAccrualService $accrualService,
        protected LeaveApprovalService $approvalService,
        protected LeaveNotificationService $notificationService,
        protected LeaveRestrictionService $restrictionService,
        protected AdvancedLeaveSettingService $advancedSettingService
    ) {}

    public function update(array $data)
    {
        $id = $data['leavetype_id'];
        $this->typeService->updateGeneral($id, $data['general'] ?? []);
        $this->accrualService->updateAccrual($id, $data['accrual'] ?? []);
        $this->approvalService->updateApproval($id, $data['approval'] ?? []);
        $this->notificationService->updateNotifications($id, $data['notifications'] ?? []);
        $this->restrictionService->updateRestriction($id, $data['restrictions'] ?? []);
        $this->advancedSettingService->updateAdvancedSetting($id, $data['advanced'] ?? []);

        return [
            'leavetype_id' => $id,
            'message' => 'All configurations updated successfully.'
        ];
        
    }

    public function delete(int $leavetype_id)
    {
        $this->accrualService->delete($leavetype_id);
        $this->approvalService->delete($leavetype_id);
        $this->notificationService->delete($leavetype_id);
        $this->restrictionService->delete($leavetype_id);
        $this->advancedSettingService->delete($leavetype_id);
    }

    public function getData(int $id){
        $leavetype_id = $id;
        $general = $this->typeService->list($id);
        $accrual = $this->accrualService->list($id);
        $approval = $this->approvalService->list($id);
        $notification = $this->notificationService->list($id);
        $restriction = $this->restrictionService->list($id);
        $advanced = $this->advancedSettingService->list($id);

        return [
            'leavetype_id' => $id,
            'general' => $general,
            'accrual' => $accrual,
            'approval' => $approval,
            'notification' => $notification,
            'restriction' => $restriction,
            'advanced' => $advanced
        ];
    }
}
