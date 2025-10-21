<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';

    protected $fillable = [
        'name',
        'status',
        'created_by',
        'updated_by'
    ];

    public function generalSetting()
    {
        return $this->hasOne(GeneralSetting::class, 'company_id');
    }
}
