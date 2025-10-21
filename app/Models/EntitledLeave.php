<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntitledLeave extends BaseModel
{
    protected $table = 'entitled_leave';

    protected $fillable = [
        'workingyears_id',
        'leavetype_id',
        'leave_balance',
        'status',
        'created_by',
        'updated_by'
    ];

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leavetype_id');
    }

    public function workingYears()
    {
        return $this->belongsTo(WorkingYears::class, 'workingyears_id');
    }
}
