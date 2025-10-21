<?php

namespace App\Services\SystemSettings;

use App\Services\BaseService;
use App\Models\ApprovalWorkflow;
use App\Models\Role;
use App\Enum\DataStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Exception;

class ApprovalWorkflowService extends BaseService
{
    public function addOrUpdate(array $data)
    {
        $isUpdate = Arr::has($data, 'id');
        if($isUpdate){
            $record = ApprovalWorkflow::where('status', '!=', DataStatus::Deleted)->where('id', $data['id'])->first();
            if (!$record) throw New Exception('Record not found');
            
            $record->fill($this->updatedBy());
        }
        else
            $record = New ApprovalWorkflow($this->createdBy());
        

        $this->message = $isUpdate ? "Update Approval Workflow" : "Add Approval Workflow";
        $record->fill($data);
        $record->fill(['workflow_string' => $this->generateWorkflowString($data)]);
        $record->save();

        return ['data' => $record, 'message' => $this->message];
    }

    public function delete(array $data)
    {
        $record = ApprovalWorkflow::where('status', '!=', DataStatus::Deleted)->where('id', $data['id'])->first();

        if($record) $this->markDeleted($record);
        else throw new Exception("Record not found");
    }

    public function list(){
        $record = ApprovalWorkflow::where('status', '!=', DataStatus::Deleted)->get();

        return $record->toArray();
    }

    private function generateWorkflowString($data)
    {
        $str = "";
        for($i = 0; $i< count($data['workflow']); $i++){
            $role = Role::find($data['workflow'][$i]);
            if($i < count($data['workflow']) - 1) 
                $str .= $role->name."->";
            else
                $str .= $role->name;
        }

        return $str;
    }
}
