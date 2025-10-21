<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalWorkflow extends Model
{
    protected $table = 'approval_workflow';

    protected $fillable = [
        'name',
        'workflow',
        'workflow_string',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'workflow' => 'array',
    ];
}
