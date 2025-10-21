<?php

namespace App\Models;

class LeaveRestriction extends BaseModel
{
    protected $table = 'leave_restriction';

    protected $fillable = [
        'leavetype_id',
        'minimum_duration',
        'maximum_duration',
        'max_consecutive_days',
        'blackout_dates',
        'required_documents',
        'department_restriction_ids',
        'roles_restriction_ids',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'blackout_dates' => 'array',
        'required_documents' => 'array',
        'department_restriction_ids' => 'array',
        'roles_restriction_ids' => 'array',
    ];

    public function setBlackoutDatesAttribute($value) 
    { 
        $this->setJsonField('blackout_dates', $value); 
    }
    public function getBlackoutDatesAttribute($value) 
    { 
        return $this->getJsonField($value); 
    }

    public function setRequiredDocumentsAttribute($value) 
    { 
        $this->setJsonField('required_documents', $value); 
    }
    public function getRequiredDocumentsAttribute($value) 
    { 
        return $this->getJsonField($value); 
    }

    public function setDepartmentRestrictionIdsAttribute($value) 
    { 
        $this->setJsonField('department_restriction_ids', $value); 
    }
    public function getDepartmentRestrictionIdsAttribute($value) 
    { 
        return $this->getJsonField($value); 
    }

    public function setRolesRestrictionIdsAttribute($value) 
    { 
        $this->setJsonField('roles_restriction_ids', $value); 
    }
    public function getRolesRestrictionIdsAttribute($value) 
    { 
        return $this->getJsonField($value); 
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leavetype_id');
    }
}
