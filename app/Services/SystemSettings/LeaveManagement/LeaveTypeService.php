<?php

namespace App\Services\SystemSettings\LeaveManagement;

use App\Services\BaseService;
use App\Models\LeaveType;
use App\Enum\DataStatus;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Services\SystemSettings\LeaveManagement\LeaveManagementService;

class LeaveTypeService extends BaseService
{
    public function updateGeneral(int $id, array $data)
    {
        $leavetype = LeaveType::find($id);
        if (!$leavetype) {
            throw new Exception("Leave type not found.");
        }

        $leavetype->update([
            'name' => $data['name'] ?? "",
            'color' => $data['color'] ?? null,
            'custom_code' => $data['custom_code'] ?? null,
            'carryforward_check' => $data['carryforward_check'] ?? 0,
            'carryforward_value' => $data['carryforward_value'] ?? 0,
            'carryforward_expiry' => $data['carryforward_expiry'] ?? null,
            'status' => $data['status'] ?? DataStatus::Active,
            'updated_by' => Auth::id(),
        ]);

        return $leavetype;
    }

    public function insert(array $data)
    {
        $record = new LeaveType($this->merge($this->createdBy(), $data));
        $record->save();

        return $record;
    }

    public function delete(int $id)
    {
        $leavetype = LeaveType::find($id);
        if(!$leavetype) throw new Exception("Leave type not found");

        $this->markDeleted($leavetype);

        $leaveManagementService = new LeaveManagementService();
        $leaveManagementService->delete($id);

    }

    public function list(int $id = 0)
    {
        $query = LeaveType::where('status', '!=', DataStatus::Deleted);
        if($id != 0){
            $leavetype = $query->where('id', $id)->first();
            if (!$leavetype) {
                throw new Exception("Record not found");
            }

            return $leavetype
                ->makeHidden(['id', 'created_by', 'updated_by', 'created_at', 'updated_at'])
                ->toArray();
        }

        return $query->select(['id', 'name', 'color', 'status'])->get()
                ->toArray();
    }

}
