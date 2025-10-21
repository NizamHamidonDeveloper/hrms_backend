<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    protected $table = 'gender';

    protected $fillable = [
        'name',
        'status',
        'created_by',
        'updated_by'
    ];
}
