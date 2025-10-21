<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profile';

    protected $fillable = [
        'user_id',
        'staffstatus_id',
        'staff_no',
        'rank',
        'gender_id',
        'position',
        'phone_no',
        'fax_no',
        'company_id',
        'department_id',
        'date_joined',
        'last_date_working',
        'workingyears_id',
        'employmenttype_id',
        'workingstate_id',
        'workinghours_id',
        'domain_id',
        'role_id',
        'status',
        'created_by',
        'updated_by'
        
    ];

    protected function casts(): array
    {
        return [
            'date_joined' => 'datetime',
            'last_date_working' => 'datetime',
        ];
    }
}


