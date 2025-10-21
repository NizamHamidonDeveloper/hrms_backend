<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $table = 'domain';

    protected $fillable = [
        'name',
        'status',
        'created_by',
        'updated_by'
    ];
}
