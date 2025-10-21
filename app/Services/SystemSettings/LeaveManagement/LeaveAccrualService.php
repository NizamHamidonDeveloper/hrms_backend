<?php

namespace App\Services\SystemSettings\LeaveManagement;

use App\Services\BaseService;
use App\Models\WorkingYears;
use App\Models\EntitledLeave;
use App\Enum\DataStatus;
use Illuminate\Support\Facades\Auth;

class LeaveAccrualService extends BaseService
{
    public function updateAccrual(int $leaveTypeId, array $accrual)
    {
        $existingIds = WorkingYears::where('leavetype_id', $leaveTypeId)
            ->where('status', '!=', DataStatus::Deleted)
            ->pluck('id')
            ->toArray();

        $incomingIds = collect($accrual)->pluck('id')->filter(fn($id) => $id != 0)->toArray();
        $toDelete = array_diff($existingIds, $incomingIds);

        if (!empty($toDelete)) {
            WorkingYears::whereIn('id', $toDelete)->update([
                'status' => DataStatus::Deleted,
                'updated_by' => Auth::id()
            ]);
            EntitledLeave::where('leavetype_id', $leaveTypeId)
                ->whereIn('workingyears_id', $toDelete)
                ->update(['status' => DataStatus::Deleted, 'updated_by' => Auth::id()]);
        }

        foreach ($accrual as $item) {
            $this->upsertAccrual($leaveTypeId, $item);
        }
    }

    private function upsertAccrual(int $leaveTypeId, array $item)
    {
        if ($item['id'] > 0) {
            $workingYear = WorkingYears::find($item['id']);
            if ($workingYear) {
                $workingYear->update([
                    'tier' => $item['tier'],
                    'updated_by' => Auth::id()
                ]);

                EntitledLeave::where('leavetype_id', $leaveTypeId)
                    ->where('workingyears_id', $workingYear->id)
                    ->update(['leave_balance' => $item['value'], 'updated_by' => Auth::id()]);
            }
        } else {
            $workingYear = WorkingYears::create([
                'leavetype_id' => $leaveTypeId,
                'tier' => $item['tier'],
                'status' => DataStatus::Active,
                'created_by' => Auth::id()
            ]);

            EntitledLeave::create([
                'leavetype_id' => $leaveTypeId,
                'workingyears_id' => $workingYear->id,
                'leave_balance' => $item['value'],
                'status' => DataStatus::Active,
                'created_by' => Auth::id()
            ]);
        }
    }

    private function delete(int $leavetype_id)
    {
        $existingIds = WorkingYears::where('leavetype_id', $leavetype_id)
            ->where('status', '!=', DataStatus::Deleted)
            ->pluck('id')
            ->toArray();

        if (!empty($existingIds)) {
            WorkingYears::whereIn('id', $existingIds)
                ->update(['status' => DataStatus::Deleted,'updated_by' => Auth::id()]);
                
            EntitledLeave::where('leavetype_id', $leavetype_id)
                ->whereIn('workingyears_id', $existingIds)
                ->update(['status' => DataStatus::Deleted, 'updated_by' => Auth::id()]);
        }
    }

    public function list(int $id)
    {
        $workingyears = WorkingYears::with(['entitledLeave'])
            ->where('status', '!=',DataStatus::Deleted)
            ->where('leavetype_id', $id)
            ->get()
            ->makeHidden(['status', 'created_by', 'updated_by', 'created_at', 'updated_at']);

        // Transform the collection
        $result = $workingyears->map(function ($year) {  
            return [
                'id'    => $year->id,
                'tier'  => $year->tier,
                'value' => $year->entitledLeave->leave_balance ?? null, // gracefully handle missing record
            ];
        });

        return $result->values();
    }
}
