<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequests extends Model
{
    protected $table = 'leave_requests';
    protected $guarded = ['id'];

    public function role() {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
