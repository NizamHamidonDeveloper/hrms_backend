<?php

namespace App\Services\SystemSettings;

use App\Services\BaseService;
use App\Models\GeneralSetting;
use App\Models\Company;
use App\Enum\DataStatus;
use Illuminate\Support\Facades\Auth;
use Exception;

class GeneralSettingService extends BaseService
{
    public function addOrUpdate(array $data)
    {
        $record = GeneralSetting::where('company_id', $data['company_id'])->where('status', '!=', DataStatus::Deleted)->first();

        if (!$record) {
            $record = new GeneralSetting($this->merge(['company_id' => $data['company_id']], $this->createdBy()));
            $this->message = "Add General Settings";
        }
        else {
            $record->fill($this->updatedBy());
            $this->message = "Update General Settings";
        }

        $record->fill([
            'leave_year_start' => $data['leave_year_start'],
            'monday_check' => $data['monday_check'] ?? 0,
            'tuesday_check' => $data['tuesday_check'] ?? 0,
            'wednesday_check' => $data['wednesday_check'] ?? 0,
            'thursday_check' => $data['thursday_check'] ?? 0,
            'friday_check' => $data['friday_check'] ?? 0,
            'saturday_check' => $data['saturday_check'] ?? 0,
            'sunday_check' => $data['sunday_check'] ?? 0
            
        ])->save();

        return ['data' => $record, 'message' => $this->message];
    }

    public function delete(array $data)
    {
        $record = GeneralSetting::where('company_id', $data['company_id'])->where('status', '!=', DataStatus::Deleted)->first();

        if($record) $this->markDeleted($record);
        else throw new Exception("Record not found");
    }

    public function list(int $id)
    {
        $generalSetting = GeneralSetting::with(['company:id,name'])
            ->where('status', '!=',DataStatus::Deleted)
            ->where('company_id', $id)
            ->first();
        
        if (!$generalSetting) {
            throw new Exception("Record not found");
        }

        // Transform the collection
        $result = $generalSetting->makeHidden(['id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at', 'company'])->toArray(); 
        $result['company_name'] = $generalSetting->company->name;

        return $result;
    }
}
