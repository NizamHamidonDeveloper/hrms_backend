<?php

namespace App\Services\SystemSettings\LeaveManagement;

use App\Services\BaseService;
use App\Models\LeaveTypeApproval;
use App\Models\RoleApproval;
use App\Enum\DataStatus;
use Illuminate\Support\Facades\Auth;
use Exception;

class LeaveApprovalService extends BaseService
{
    public function updateApproval(int $leaveTypeId, array $approval)
    {
        $record = LeaveTypeApproval::firstOrNew(
            ['leavetype_id' => $leaveTypeId],
            ['status' => DataStatus::Active, 'created_by' => Auth::id()]
        );

        $record->fill([
            'autoapproval_check' => $approval['autoapproval_check'] ?? 0,
            'escalation_role_id' => $approval['escalation_role_id'] ?? null,
            'escalation_days' => $approval['escalation_days'] ?? null,
            'updated_by' => Auth::id()
        ])->save();

        $existingIds = RoleApproval::where('leavetypeapproval_id', $record->id)
            ->where('status', '!=', DataStatus::Deleted)
            ->pluck('id')
            ->toArray();

        $incomingIds = collect($approval['role_approval'])->pluck('id')->filter(fn($id) => $id != 0)->toArray();
        $toDelete = array_diff($existingIds, $incomingIds);

        if (!empty($toDelete)) {
            RoleApproval::whereIn('id', $toDelete)->update([
                'status' => DataStatus::Deleted,
                'updated_by' => Auth::id()
            ]);
        }

        foreach ($approval['role_approval'] as $item) {
            $this->upsertRoleApproval($record->id, $item);
        }
    }

    private function upsertRoleApproval(int $leavetypeapproval_id, array $item)
    {
        if ($item['id'] > 0) {
            $roleApproval = RoleApproval::find($item['id']);
            if ($roleApproval) {
                $roleApproval->update([
                    'approvalworkflow_id' => $item['approvalworkflow_id'] ?? null,
                    'role_id' => $item['role_id'] ?? 0,
                    'updated_by' => Auth::id(),
                ]);
            }
        } else {
            $roleApproval = RoleApproval::create([
                'leavetypeapproval_id' => $leavetypeapproval_id,
                'approvalworkflow_id' => $item['approvalworkflow_id'] ?? null,
                'role_id' => $item['role_id'] ?? 0,
                'status' => DataStatus::Active,
                'created_by' => Auth::id(),
            ]);
        }
    }

    protected function delete(int $leavetype_id)
    {
        $existingIds = LeaveTypeApproval::where('leavetype_id', $leavetype_id)
            ->where('status', '!=', DataStatus::Deleted)
            ->pluck('id')
            ->toArray();

        if (!empty($existingIds)) {
            LeaveTypeApproval::whereIn('id', $existingIds)
                ->update(['status' => DataStatus::Deleted,'updated_by' => Auth::id()]);
                
            RoleApproval::whereIn('leavetypeapproval_id', $existingIds)
                ->update(['status' => DataStatus::Deleted, 'updated_by' => Auth::id()]);
        }
    }

    public function list(int $id)
    {
        $approval = LeaveTypeApproval::with(['roleApproval'])
            ->where('status', '!=',DataStatus::Deleted)
            ->where('leavetype_id', $id)
            ->first()
            ?->makeHidden(['id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at']);

        // Transform the collection
        $result = $approval? [
            'autoapproval_check'    => $approval->autoapproval_check,
            'escalation_role_id'  => $approval->escalation_role_id,
            'escalation_days' => $approval->escalation_days,
            'role_approval' => $approval->roleApproval->map(function ($role){
                return [
                    'role_id' => $role->role_id, 
                    'approvalworkflow_id' => $role->approvalworkflow_id
                ];
            }),
        ] : null;

        return $result;
    }
}
