<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    protected $table = 'system_log';

    protected $fillable = [
        'user_id',
        'action',
        'action_detail',
        'url',
        'post_data',
        'get_data',
        'controller_name',
        'method_name',
        'log_type',
        'status',
        'created_by',
        'updated_by'
    ];
}
