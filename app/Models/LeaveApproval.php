<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveApproval extends Model
{
    protected $table = 'leave_approval';
    protected $guarded = ['id'];

    public function role() 
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
