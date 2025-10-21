<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends BaseModel
{
    protected $table = 'general_setting';

    protected $fillable = [
        'company_id',
        'leave_year_start',
        'monday_check',
        'tuesday_check',
        'wednesday_check',
        'thursday_check',
        'friday_check',
        'saturday_check',
        'sunday_check',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'monday_check' => 'boolean',
        'tuesday_check' => 'boolean',
        'wednesday_check' => 'boolean',
        'thursday_check' => 'boolean',
        'friday_check' => 'boolean',
        'saturday_check' => 'boolean',
        'sunday_check' => 'boolean'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
