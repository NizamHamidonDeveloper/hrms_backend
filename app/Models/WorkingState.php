<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingState extends Model
{
    protected $table = 'working_state';

    protected $fillable = [
        'name',
        'status',
        'created_by',
        'updated_by'
    ];
}
