<?php

namespace App\Services\SystemSettings\LeaveManagement;

use App\Services\BaseService;
use App\Models\LeaveNotification;
use App\Enum\DataStatus;
use Illuminate\Support\Facades\Auth;

class LeaveNotificationService extends BaseService
{
    public function updateNotifications(int $leaveTypeId, array $data)
    {
        $record = LeaveNotification::where('leavetype_id', $leaveTypeId)->where('status', '!=', DataStatus::Deleted)->first();
        if (!$record)
            $record = new LeaveNotification($this->merge(['leavetype_id' => $leaveTypeId], $this->createdBy()));
        else
            $record->fill($this->updatedBy());

        $record->fill([
            'onapply_check' => $data['onapply_check'] ?? 0,
            'onapproval_check' => $data['onapproval_check'] ?? 0,
            'onrejection_check' => $data['onrejection_check'] ?? 0,
            'oncancelation_check' => $data['oncancelation_check'] ?? 0,
            'channelemail_check' => $data['channelemail_check'] ?? 0,
            'channelapp_check' => $data['channelapp_check'] ?? 0,
            'additional_recepients' => $data['additional_recepients'] ?? [],
            'notification_template' => $data['template'] ?? null
        ])->save();
    }

    public function delete(int $leavetype_id)
    {
        $record = LeaveNotification::where('leavetype_id', $leavetype_id)->where('status', '!=', DataStatus::Deleted)->first();
        if($record) $this->markDeleted($record);
    }

    public function list(int $id){
        $record = LeaveNotification::where('leavetype_id', $id)->where('status', '!=', DataStatus::Deleted)->first();
        
        return $record ? $record->makeHidden(['id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'])->toArray() : null;
    }
}
