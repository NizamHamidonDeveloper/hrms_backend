<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleApproval extends BaseModel
{
    protected $table = 'role_approval';

    protected $fillable = [
        'leavetypeapproval_id',
        'role_id',
        'approvalworkflow_id',
        'status',
        'created_by',
        'updated_by'
    ];

    public function leaveTypeApproval()
    {
        return $this->belongsTo(LeaveTypeApproval::class, 'leavetypeapproval_id');
    }
}
