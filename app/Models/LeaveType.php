<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends BaseModel
{
    protected $table = 'leave_type';
    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'color',
        'custom_code',
        'entitlementtype_id',
        'carryforward_check',
        'carryforward_value',
        'carryforward_expiry',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'carryforward_check' => 'boolean'
    ];

    // Relationships
    public function workingYears()
    {
        return $this->hasMany(WorkingYears::class, 'leavetype_id');
    }

    public function entitledLeaves()
    {
        return $this->hasMany(EntitledLeave::class, 'leavetype_id');
    }

    public function restrictions()
    {
        return $this->hasOne(LeaveRestriction::class, 'leavetype_id');
    }

    public function notifications()
    {
        return $this->hasOne(LeaveNotification::class, 'leavetype_id');
    }

    public function approval()
    {
        return $this->hasOne(LeaveTypeApproval::class, 'leavetype_id');
    }

    public function advancedSetting()
    {
        return $this->hasOne(advancedSetting::class, 'leavetype_id');
    }

}
