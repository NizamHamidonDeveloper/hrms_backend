<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffStatus extends Model
{
    protected $table = 'staff_status';

    protected $fillable = [
        'name',
        'status',
        'created_by',
        'updated_by'
    ];
}
