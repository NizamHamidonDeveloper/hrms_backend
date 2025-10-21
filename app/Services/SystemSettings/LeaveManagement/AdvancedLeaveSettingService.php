<?php

namespace App\Services\SystemSettings\LeaveManagement;

use App\Services\BaseService;
use App\Models\AdvancedSetting;
use App\Enum\DataStatus;
use Illuminate\Support\Facades\Auth;

class AdvancedLeaveSettingService extends BaseService
{
    public function updateAdvancedSetting(int $leaveTypeId, array $data)
    {
        $record = AdvancedSetting::where('leavetype_id', $leaveTypeId)->where('status', '!=', DataStatus::Deleted)->first();
        if (!$record)
            $record = new AdvancedSetting($this->merge(['leavetype_id' => $leaveTypeId], $this->createdBy()));
        else
            $record->fill($this->updatedBy());

        $record->fill([
            'prorata_calculation_check' => $data['prorata_calculation_check'] ?? 0,
            'negative_balance_check' => $data['negative_balance_check'] ?? 0,
            'audit_logging_check' => $data['audit_logging_check'] ?? 0,
            'leave_encashment_check' => $data['leave_encashment_check'] ?? 0,
            'encashment_maximum_day' => $data['encashment_maximum_day'] ?? 0,
            'custom_leave_code' => $data['channelapp_check'] ?? null
        ])->save();
    }

    public function delete(int $leavetype_id)
    {
        $record = AdvancedSetting::where('leavetype_id', $leavetype_id)->where('status', '!=', DataStatus::Deleted)->first();
        if($record) $this->markDeleted($record);
    }

    public function list(int $id){
        $record = AdvancedSetting::where('leavetype_id', $id)->where('status', '!=', DataStatus::Deleted)->first();
        
        return $record ? $record->makeHidden(['id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'])->toArray() : null;
    }
}
