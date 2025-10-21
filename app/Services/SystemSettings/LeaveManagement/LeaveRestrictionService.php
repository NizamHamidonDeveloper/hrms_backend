<?php

namespace App\Services\SystemSettings\LeaveManagement;

use App\Services\BaseService;
use App\Models\LeaveRestriction;
use App\Enum\DataStatus;
use Illuminate\Support\Facades\Auth;

class LeaveRestrictionService extends BaseService
{
    public function updateRestriction(int $leaveTypeId, array $data)
    {
        $record = LeaveRestriction::where('leavetype_id', $leaveTypeId)->where('status', '!=', DataStatus::Deleted)->first();

        if (!$record)
            $record = new LeaveRestriction($this->merge(['leavetype_id' => $leaveTypeId], $this->createdBy()));
        else
            $record->fill($this->updatedBy());

        $record->fill([
            'minimum_duration' => $data['minimum_duration'] ?? null,
            'maximum_duration' => $data['maximum_duration'] ?? null,
            'max_consecutive_days' => $data['max_consecutive_days'] ?? null,
            'blackout_dates' => $data['blackout_dates'] ?? [],
            'required_documents' => $data['required_documents'] ?? null,
            'department_restriction_ids' => $data['department_restriction_ids'] ?? [],
            'roles_restriction_ids' => $data['roles_restriction_ids'] ?? []
        ])->save();
    }

    public function delete(int $leavetype_id)
    {
        $record = LeaveRestriction::where('leavetype_id', $leavetype_id)->where('status', '!=', DataStatus::Deleted)->first();
        if($record) $this->markDeleted($record);
    }

    public function list(int $id){
        $record = LeaveRestriction::where('leavetype_id', $id)->where('status', '!=', DataStatus::Deleted)->first();
        
        return $record ? $record->makeHidden(['id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'])->toArray() : null;
    }
}
