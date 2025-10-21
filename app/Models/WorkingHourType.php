<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingHourType extends Model
{
    protected $table = 'working_hour_type';

    protected $fillable = [
        'name',
        'status',
        'created_by',
        'updated_by'
    ];
}
