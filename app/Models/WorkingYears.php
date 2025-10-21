<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enum\DataStatus;

class WorkingYears extends BaseModel
{
    protected $table = 'working_years';

    protected $fillable = [
        'leavetype_id',
        'tier',
        'status',
        'created_by',
        'updated_by'
    ];

   public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leavetype_id');
    }

    public function entitledLeave()
    {
        return $this->hasOne(EntitledLeave::class, 'workingyears_id')
                    ->where('status', '!=', DataStatus::Deleted);
    }

    public function entitledLeaveAll()
    {
        return $this->hasOne(EntitledLeave::class, 'workingyears_id');
    }
}
