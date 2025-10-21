<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvancedSetting extends BaseModel
{
    protected $table = 'advanced_setting';

    protected $fillable = [
        'leavetype_id',
        'prorata_calculation_check',
        'negative_balance_check',
        'audit_logging_check',
        'leave_encashment_check',
        'encashment_maximum_day',
        'custom_leave_code',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'prorata_calculation_check' => 'boolean',
        'negative_balance_check' => 'boolean',
        'audit_logging_check' => 'boolean',
        'leave_encashment_check' => 'boolean',
    ];

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leavetype_id');
    }
}
