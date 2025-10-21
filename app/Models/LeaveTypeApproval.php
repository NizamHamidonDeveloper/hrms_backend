<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enum\DataStatus;

class LeaveTypeApproval extends BaseModel
{
    protected $table = 'leavetype_approval';

    protected $fillable = [
        'leavetype_id',
        'autoapproval_check',
        'escalation_role_id',
        'escalation_days',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'autoapproval_check' => 'boolean'
    ];

    public function roleApproval()
    {
        return $this->hasMany(RoleApproval::class, 'leavetypeapproval_id')
                    ->where('status', '!=', DataStatus::Deleted);
    }

    public function roleApprovalAll()
    {
        return $this->hasMany(RoleApproval::class, 'leavetypeapproval_id');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leavetype_id');
    }
}
