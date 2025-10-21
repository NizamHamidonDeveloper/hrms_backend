<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrentLeave extends Model
{
    protected $table = 'current_leave';

    protected $fillable = [
        'user_id',
        'year',
        'leavetype_id',
        'leave_balance',
        'status',
        'created_by',
        'updated_by'
    ];
}
