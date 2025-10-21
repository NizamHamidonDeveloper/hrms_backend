<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    protected $table = 'leave_application';
    protected $guarded = ['id'];

    public function leave_requests(){
        return $this->hasMany(LeaveRequests::class, 'leave_application_id', 'id');
    }
}
